<tr ng-repeat="task in tasks">   
    <td>{{ task.name }}</td>
    <td>{{ task.dateAdded }}</td>
    <td class="{{task.status === 'completed' ? 'text-success': 'text-pending'}}">{{ task.status }}</td>
    <td>
        <input type="hidden" value="{{task.id}}" class="taskId"/>
        <div class="form-check">
            <input type="checkbox" class="form-check-input taskStatus" ng-click="changeStatus($index)" value="{{task.status}}"/>
        </div>
        <input type="hidden" value="{{task.name}}" class="task-Name"/>
    </td>
    <td>
        <button class="btn btn-warning task-control-btn updateBtn" data-toggle="modal" data-target="#modalUpdateTask" ng-click="setTaskUpdate($index)">
            <i class="{:val.updateIcon}"></i>
        </button>
    </td>
    <td>
        <button class="btn btn-danger task-control-btn delBtn" ng-click="deleteTask($index)">
            <i class="{:val.deleteIcon}"></i>
        </button>
    </td>
</tr>