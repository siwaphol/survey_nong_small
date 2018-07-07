function isLastItem(item, parent) {
    if (parent.find(item).length<=1){
        swal("ต้องเหลือข้อมูลอย่างน้อย 1 ข้อมูล","","warning")
        return false;
    }
    return true;
}