var app = angular.module('appControl', []);

app.controller('appController', function(){

    this.id = -1;

    this.listings = [];

    this.loadListings = function() {
        $http.get('listings').then(function(data) {
            this.listings = data;
        });
    };

    this.selectListing = function($id) {
        this.id = $id;
        console.log($id);
    };

});
