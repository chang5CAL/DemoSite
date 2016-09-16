var jdEvents = jdEvents || {};


JD_EVENT_NAME_Reload = "jdreloadevent";
JD_EVENT_NAME_Reset = "jdresetevent";



/**
 *
 * @param aComponentConfig
 * @param aComponent
 */
jdEvents.initEventListeners = function(aComponentConfig, aComponent) {
    console.log("Registering other components that " + aComponentConfig.IdConfig.ElementId + "  will listen  for reload , resets on");

    jdEvents.registerEventListeners(aComponentConfig.IdConfig.ParentIds, aComponent);
};


/**
 * If your component has reloaded and you want to let registered watching children and parents know about it call this.
 * @param aComponentIdThatReloaded
 */
jdEvents.triggerReloadEvent = function(aComponentIdThatReloaded) {
    console.log(aComponentIdThatReloaded + " has reloaded. Sending event to listeners");
    $("#" + aComponentIdThatReloaded).trigger(JD_EVENT_NAME_Reload);

};


/**
 * If your component has reset and you want to let registered watching children and parents know about it call this.
 * @param aComponentIdThatReset
 */
jdEvents.triggerResetEvent = function(aComponentIdThatReset) {
    console.log("So :" + aComponentIdThatReset + ": has reset. Sending event to listeners");
    $("#" + aComponentIdThatReset).trigger(JD_EVENT_NAME_Reset);
};


/**
 * Used to check the parentIds and ChildIds in the coniguration JSON to see if there any set and if they have the flags
 * set to so this componenent should register itself as a listener for relaod or reset events from it.
 * If no configuration put it defaults to not adding listeners.
 *
 * Examples :
 * {"ElementId":"form1", "WatchForReloads" : true,  "WatchForResets" : true } >> listen on both reloads and resets
 * {"ElementId":"form1", "WatchForReloads" : false,  "WatchForResets" : true } >> listen on just reloads
 * {"ElementId":"form1"} >> listn on none
 *
 * Currently used for Reset and Reload Events and sends events to configured ChildID and ParentID components.
 * This could be generalised in time for being all types of events from components
 * @param aComponentIdsToCheck
 * @param aComponent
 */
jdEvents.registerEventListeners = function(aComponentIdsToCheck, aComponent) {

    if (jdEvents.isNotNull(aComponentIdsToCheck)) {
        for (var i = 0; i < aComponentIdsToCheck.length; i++) {
            var currentObject = aComponentIdsToCheck[i];
            var currentId = currentObject["ElementId"];
            if (jdEvents.isNotNull(currentId)) {
                // Register reload event listeners
                if(currentObject.WatchForReloads === true) {
                    console.log("Listing for relaods by :" + currentId);
                    $("#" + currentId).on(JD_EVENT_NAME_Reload, { component: aComponent},
                        function (e) {
                            console.log("RELOAD EVENT RECIEVED FOR:" + e.currentTarget.id);
                            e.data.component.onReloadEvent();
                        }
                    );
                }

                // Register reset event listeners
                if((currentObject.WatchForResets === true)) {
                    console.log("Listing for resets by :" + currentId);
                    $("#" + currentId).on(JD_EVENT_NAME_Reset, { component: aComponent},
                        function (e) {
                            console.log("RESET EVENT RECEIVED FOR :" + e.currentTarget.id);
                            e.data.component.onResetEvent();
                        }
                    );
                }
            }
        }
    }
};








/**
 * Check if null (undefined handled to)
 * @param value to check
 * @returns {boolean}
 */
jdEvents.isNull = function (value) {
    var nullCheck = true;
    if (typeof value != 'undefined' && value) {
        nullCheck = false;
    }
    return nullCheck;
};

/**
 * Check if not null (undefined handled to)
 * @param value to check
 * @returns {boolean}
 */
jdEvents.isNotNull = function (value) {
    return !jdEvents.isNull(value);
};
