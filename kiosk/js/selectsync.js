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

/*
* 
*/
function templateTypeSelect(obj) {
    var val = parseInt(obj.value, 10);
    var i, target, descimg, location;

    switch (val) {
        case 0:
            target = false;
            descimg = false;
            location = false;
            descetc = false;
            break;
        case 1:
            target = true;
            descimg = false;
            location = true;
            descetc = true;
            break;
        case 2:
 //       case 3:
            target = false;
            descimg = true;
            location = true;
            descetc = true;
            break;
        default:
            alert('지정된 타입이 아닙니다.');
            return;
    }

    var cb = document.getElementsByName("target[]");
    var cb2 = document.getElementsByName("descimg[]");
    var cb3 = document.getElementsByName("location[]");
    var cb4 = document.getElementsByName("descetc[]");
    for (i = 0 ; i < cb.length ; i++) {
        cb[i].disabled = !target;
        cb2[i].disabled = !descimg;
        cb3[i].disabled = !location;
        cb4[i].disabled = !descetc;
    }

    var count;
    switch (val) {
        case 0:
        case 1:
            count = 1;
            break;
        case 2:
            count = 6;
            break;
        default:
//            count = 9;
            break;
    }
    
    cb = document.getElementsByName("file[]");
    for (i = 0, count *= 4; i < count ; i++) {
        cb[i].disabled = false;
    }
    for ( ; i < cb.length ; i++) {
        cb[i].disabled = true;
    }
}

function pxstr2int(str) {
    return parseInt(str.split('p')[0], 10);
}

var idxcoordsel = 0;
function mapShow(idx) {
    var hall = document.getElementsByName("hall[]")[idx];
    var floor = document.getElementsByName("floor[]")[idx];
    var xpos = document.getElementsByName("xpos[]")[idx];
    var ypos = document.getElementsByName("ypos[]")[idx];
    var mappath = './images/static/common/map_' + hall.value + (floor.value == 'B1F' ? 1 : parseInt(floor.value.replace("F", ""), 10) + 1) + '_' + floor.value + '.png';

    idxcoordsel = idx;
    map.src = mappath;
    here.style.left = (pxstr2int(map.style.left) + parseInt(xpos.value) - 6) + "px";
    here.style.top = (pxstr2int(map.style.top) + parseInt(ypos.value) - 6) + "px";
    mapview.style.visibility = 'visible';
}

function coordinateSelect() {
    here.style.left = (event.clientX - 6) + "px";
    here.style.top = (event.clientY - 6) + "px";
}

function coordinateApply() {
    var xpos = document.getElementsByName("xpos[]")[idxcoordsel];
    var ypos = document.getElementsByName("ypos[]")[idxcoordsel];
    xpos.value = pxstr2int(here.style.left) - pxstr2int(map.style.left) + 6;
    ypos.value = pxstr2int(here.style.top) - pxstr2int(map.style.top) + 6;
}
