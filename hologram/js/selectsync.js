/*
 * 
 */
function selectionSync(obj) {
    var val = obj.selectedIndex;
    var cb = document.getElementsByName("conssel[]");
    
    for (var i = 0; i < cb.length; i++) {
        if (val != cb[i].selectedIndex) {
            cb[i].selectedIndex = val;
        }
    }
}
