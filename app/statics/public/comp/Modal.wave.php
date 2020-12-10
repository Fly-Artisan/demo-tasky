<!--Modal: Login with Avatar Form-->
<div class="modal fade" id={:str.targetId} tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
    <!--Content-->
    <div class="modal-content">

      <!--Header-->
      
      <!--Body-->
      <div class="modal-body text-center mb-1">

        @if {:val.saveModal}:
            <h5 class="mt-1 mb-2">Add Your Task</h5>
        @else:
            <h5 class="mt-1 mb-2">Update Task</h5>
        @endif
        <div class="md-form ml-0 mr-0">
          <input id="taskName" type="text" ng-model="name" class="form-control form-control-sm validate ml-0" autofocus>
          {~ @csrf ~}
          <label data-error="wrong" data-success="right" for="form29" class="ml-0">Enter task</label>
          <input type="hidden" ng-model="taskID" id="form29" class="form-control form-control-sm validate ml-0">
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-success mt-1" ng-click="{~ {:val.saveModal} ? 'saveTask()': 'updateTask()' ~}">
            {~
              {:val.saveModal} 
                ? 
                    'Save <i class="fa fa-save"></i>'
                : 
                    'Update <i class="fa fa-edit"></i>'
            ~}
        </button>
        </div>
      </div>

    </div>
    <!--/.Content-->
  </div>
</div>
