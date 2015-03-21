/*
 * 
 */
function updateClicked() {
    alert("업데이트생성");
}

function toggleCheckbox(checked, name) {
    var cb = document.getElementsByName(name);
    
    for (var i = 0 ; i < cb.length; i++) {
        cb[i].checked = checked;
    }

}

function testCheckbox(name) {
    var cb = document.getElementsByName(name);

    for (var i = 0; i < cb.length; i++) {
        if (cb[i].checked) {
            return true;
        }
    }

    alert("선택한 항목이 없습니다");
    return false;
}
