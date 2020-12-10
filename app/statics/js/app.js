
const app = angular.module('taskApp',[]);

const Spinner = {
    on: ($scope) => { 
        $scope.dtBasicStyle = 'display: none;';
        $scope.spinnerStyle = '';
    },
    off: ($scope) => {
        $scope.dtBasicStyle = '';
        $scope.spinnerStyle = 'display: none;';
    }

}

app.controller('taskAppCtrl',function($scope,$http) {
    Spinner.on($scope);
    fetchTask($scope,$http);

    $scope.saveTask      = () => addTask($scope,$http);

    $scope.updateTask    = () => updateTask($scope,$http);

    $scope.deleteTask    = ($index) => deleteTask($index,$scope,$http);

    $scope.changeStatus  = ($index) => changeStatus($index,$scope,$http);
    $scope.setTaskUpdate = ($index) => {
        $scope.taskID = document.querySelectorAll('.taskId')[$index].value;
        $scope.name   = document.querySelectorAll('.task-Name')[$index].value;
        console.log($scope.taskID);
    }
    $scope.focusToAdd  = () => {
        $scope.name = "";
        $scope.taskID = "";
        document.querySelector('#taskName').focus();
    }
});

function inspectStatusCheckBox(interval) {
    const checkboxes = document.querySelectorAll('.taskStatus');
    let flag = false;
    checkboxes.forEach(el => {
        el.checked = el.value === 'completed';
        flag = true;
    });
    if(flag) clearInterval(interval);
}

function fetchTask($scope,$http) {

    $http.get(setUrl('task/fetch'))
    .then((res) => {
        $scope.tasks = res.data.tasks;
        Spinner.off($scope);
        const interval = setInterval(() => {
            inspectStatusCheckBox(interval);
        },100);
    })
}

function changeStatus($index,$scope,$http) {
    const token = document.querySelectorAll('input[name="csrf_token"]')[0].value;
    const id    = document.querySelectorAll('.taskId')[$index].value;
    const status= document.querySelectorAll('.taskStatus')[$index].checked ? 'completed': 'pending';
    
    servo.post({
        url: setUrl('task/status/update'),
        async: true,
        jsonParse: true,
        send: [{id, status ,csrf_token: token}]
    }).then(({response}) =>{
        alert(response.payload);
        fetchTask($scope,$http);
    }).catch(({error}) => console.log(error));
}

function addTask($scope,$http) {
    const token = document.querySelectorAll('input[name="csrf_token"]')[0].value;
    servo.post({
        url: setUrl('task/add'),
        async: true,
        jsonParse: true,
        send: [{name: $scope.name, csrf_token: token}]
    }).then(({response}) =>{
        alert(response.payload);
        fetchTask($scope,$http);
    }).catch(({error}) => console.log(error));

}

function deleteTask($index,$scope,$http) {
    const token = document.querySelectorAll('input[name="csrf_token"]')[0].value;
    servo.post({
        url: setUrl('task/delete'),
        async: true,
        jsonParse: true,
        send: [{taskNum: ($index + 1), csrf_token: token}]
    }).then(({response}) =>{
        alert(response.payload);
        fetchTask($scope,$http);
    }).catch(({error}) => console.log(error));
}

function updateTask($scope,$http) {

    const token = document.querySelectorAll('input[name="csrf_token"]')[0].value;
    servo.post({
        url: setUrl('task/update'),
        async: true,
        jsonParse: true,
        send: [{id: $scope.taskID, name: $scope.name, csrf_token: token}]
    }).then(({response}) =>{
        alert(response.payload);
        fetchTask($scope,$http);
    }).catch(({error}) => console.log(error));
}