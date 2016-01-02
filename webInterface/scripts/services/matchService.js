var module = angular.module('myapp', []);
 
module.service('userService', function(){
    this.users = ['John', 'James', 'Jake'];
});