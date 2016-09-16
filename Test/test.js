/**
 * Created by mzdw4w on 16/09/2016.
 */

$(document).ready(function () {


    var configPath  = "./";
    $.when(
        $.getJSON(configPath + "exampleform.table1.json"),
        $.getJSON(configPath + "exampleform.table2.json")
    )
        .then(function (table1ConfigResponse, table2ConfigResponse) {
            return buildUI(table1ConfigResponse[0], table2ConfigResponse[0])
        })
        .then(function () {
           //fall through
        });


});





var buildUI = function (table1Config, table2Config) {

    // Add listeners - need all parents and child to of been created before adding watches on IDs
    jdEvents.initEventListeners(table1Config, new jdTable(table1Config));
    jdEvents.initEventListeners(table2Config, new jdTable(table2Config));

};


