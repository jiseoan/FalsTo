/*
 * 
 */
function onMenuSelect(index) {
    switch (index) {
        case 2:
            document.location.replace("mainmansch.php");
            break;
        case 3:
            document.location.replace("mainmansvc.php");
            break;
        case 4:
            document.location.replace("mainmanadm.php");
            break;
        default:
            document.location.replace("mainman.php");
            break;
    }
}
