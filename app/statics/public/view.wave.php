<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {~ usejs('libs/angular/angular.min') ~}
    {~ usecss('libs/mdb/css/bootstrap.min') ~}
    {~ usecss('libs/mdb/css/mdb.min') ~}
    {~ usecss('fonts/font-awesome/css/font-awesome.min') ~}
    @usecss('style')
    <title>{~ $app_name ~}</title>
</head>
<body ng-app="{:val.ngApp}" ng-controller={:str.ngCtrl}>
    {:children}
    {~ usejs('libs/brew/lib',['data-main' => statics('js/src',false)]) ~}
    {~ usejs('libs/brew/servo') ~}
    {~ usejs('libs/mdb/js/jquery.min') ~}
    {~ usejs('libs/mdb/js/bootstrap.min') ~}
    {~ usejs('libs/mdb/js/mdb.min') ~}
    @usejs('app')
</body>
</html>
