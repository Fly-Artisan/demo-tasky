<?php namespace App\Actors\VDT;
use FLY\Libs\{Request, Validator};
use App\Models\taskdb\DS\Task;
use FLY\Libs\Restmodels\Dto;

class UserVdt extends Validator {

	public function addTask()
	{
        $this->response = Dto::set(['state' => false, 'message' => 'Task already exists.']);
		if($this->validator->has_error()) {
			return $this->validator->get_error_message();
		} else if(!Task::value_exists($this->request->name)) {
            Task::auto_save();
            $this->response = Dto::set(['state' => true, 'message' => 'Task was added successfully.']);
        }
        return $this->response;
	}
    
    public function deleteTask()
    {
        if(Task::instance()->pop($this->request::get('taskNum'))) {
            return Dto::set(['state' => true, 'message' => 'Task was deleted.']);
        }
        return Dto::set(['state' => false, 'message' => 'Task deos not exists.']);
    }
    
    public function changeStatus() 
    {
        if($this->validator->has_error()) {
			return $this->validator->get_error_message();
        }
        Task::auto_update();
        $task = Task::get($this->request::get('id'));
        return Dto::set(['state' => true, 'message' => $task->name.' is '.$task->status]);
    }

    public static function viewTask()
    {
        return ['tasks' => Task::all()];
    }
    
	protected function error_report(): array
	{
		return [
            'taskNum:num'                => 'Access Denied!!!. Cannot delete task',
			'name:alphaNum'              => 'Your task name must be alpha numeric.',
            'name:max|25|'               => 'Task name must not exceed 25.',
            'status:(completed,pending)' => 'Invalid status.'
		];
	}

    /**
     * @param Request $request
     *
     * @return UserVdt|__anonymous@408
     *
     * @Todo Purposely to execute use cases with optional validations
     */
    static function _(Request $request)
    {
        return new class($request) extends UserVdt {

            public function updateTask()
            {
                $status = $this->changeStatus();
            
                return (
                    !$status->getState()
                        ? 
                        fn() => $status
                        : 
                        function() use($status) {
                            $status->setMessage('Task was updated successfully.');
                            return $status;
                        }
                )();
            }

            protected function error_report():array
            {
                return [
                    'name:?alphaNum' => 'Task name must alphabet or alpha numeric.',
                    'name:?max|25|'   => 'Task name must not exceed 25.'
                ];
            }

        };
    }
}

