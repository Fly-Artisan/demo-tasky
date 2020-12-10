<?php namespace App\Actors;
use FLY\Libs\{Request, Validator};
use App\Models\taskdb\DS\Task;

class User extends Validator {

	public function addTask()
	{
        $this->response = ['state' => false, 'payload' => 'Task already exists.'];

		if($this->validator->has_error()) {
			return $this->validator->get_error_message();
		}else if(!Task::value_exists($this->request->name)) {
            Task::auto_save();
            $this->response = ['state' => true, 'payload' => 'Task was added.'];
        }
        return $this->response;
	}
    
    public function deleteTask()
    {
        if(Task::instance()->pop($this->request::get('taskNum'))) {
            return ['state' => true, 'payload' => 'Task was deleted.'];
        }
        return ['state' => false, 'payload' => 'Task already deos not exists.'];
    }
    
    public function changeStatus() 
    {
        if($this->validator->has_error()) {
			return $this->validator->get_error_message();
        }
        Task::auto_update();
        $task = Task::get($this->request::get('id'));
        return ['state' => true, 'payload' => $task->name.' is '.$task->status];
    }

    public static function viewTask()
    {
        return ['tasks' => Task::all()];
    }
    
	protected function error_report():array
	{
		return [
            'taskNum:num'                => 'Access Denied!!!. Cannot delete task',
			'name:alphaNum'              => 'Please enter your task name.',
            'name:max|25|'               => 'Task name does not exceed 25.',
            'status:(completed,pending)' => 'Invalid status.'
		];
	}


    /**
     * @param Request $request
     *
     * @return User|__anonymous@408
     *
     * @Todo Purposely to execute use cases with optional validations
     */
    static function _(Request $request)
    {
        return new class($request) extends User {

            public function updateTask()
            {
                $status = $this->changeStatus();
            
                return (
                    !$status['state'] 
                        ? 
                        fn() => $status
                        : 
                        function() use($status) {
                            $status['payload'] = 'Task was updated.';
                            return $status;
                        }
                )();
            }

            protected function error_report():array
            {
                return [
                    'name:?alphaNum' => 'Task name must alphabet or alpha numeric.'
                ];
            }

        };
    }
}

