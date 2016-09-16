/*
 *
 */


var jdTable = function (config) {

    this.tableId = undefined;
    this.parentId = undefined;

    /*
     *
     */
    this.initialize = function (config) {
        this.parentId = config.IdConfig.ContainerElementId;
        this.tableId = config.IdConfig.ElementId;
        $("#" + this.parentId).append('<table class="table" id="' + this.tableId + '"><tr><td><div id="reloadlabel' + this.tableId + '">TEST</div></td></tr></table>');
        var reloadAt = "Table: " +  this.tableId + "            Reloaded At: " + getFormattedDate();
        $("#" + this.parentId).append('<input id="btnSubmit' + this.tableId + '" type="submit" value="RELOAD ME" />');
        $("#reloadlabel" + this.tableId).text(reloadAt);
        $("#btnSubmit" + this.tableId).click({param1: this.tableId }, function (e) {
            alert("reload button pressed for :" + e.data.param1)
            jdTable.reloadTableData(e.data.param1);
        });

    };


    jdTable.reloadTableData = function(tableReloading) {
        var reloadAt = "Table: " +  tableReloading + "            Is Now Reloaded At: " + getFormattedDate();
        $("#reloadlabel" + tableReloading).text(reloadAt);
        alert("about to fire notification that this has reloaded " + tableReloading);

        jdEvents.triggerReloadEvent(tableReloading);
    };




    /**
     * Called on component by jdevents when resetEvent is fired on a componnent this is listenting on
     */
    this.onResetEvent = function() {
        // nothing
    };


    /**
     * Called on component by jdevents when reloadEvent is fired on a componnent this is listenting on
     */
    this.onReloadEvent = function() {
        alert(this.tableId +  " has had its onReloadEventCalled");
        jdTable.reloadTableData(this.tableId);
    };

    this.initialize(config);
};


function getFormattedDate() {
    var date = new Date();
    var str = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

    return str;
}