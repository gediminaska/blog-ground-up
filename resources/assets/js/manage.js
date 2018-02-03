const accordions = document.getElementsByClassName('has-submenu');

for (var i = 0; i < accordions.length; i++) {
    accordions[i].onclick = function () {
        this.classList.toggle('is-active');

        const submenu = this.nextElementSibling;
        if(submenu.style.maxHeight) {
            //if the menu is open
            submenu.style.maxHeight = null;
            submenu.style.marginTop = null;
            submenu.style.marginBottom = null
        } else {
            //in the menu is closed
            submenu.style.maxHeight = submenu.scrollHeight + "px";
            submenu.style.marginTop = "0.75rem"
            submenu.style.marginBottom = "0.75rem"
        }
    }
}