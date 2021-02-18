    // variables
    let checkBox = document.getElementById("checkbox");
    let navMob = document.getElementById("navLinks");
    let mobileLogoNav = document.getElementById("mobile-logo-nav");
    //let menuToggle = document.getElementById("menuToggle");
    let mobileItems = document.getElementsByClassName("hover-underline");

    // function to not show mobile navigation if checkbox is not checked
    function noShowMobileNav () {
        let mq = window.matchMedia("(max-width:930px)");
        let windowSize;
        if (mq.matches) {
            windowSize = "small";
        } else {
            windowSize = "large";
        }

        if (windowSize === "small") {
            checkBox.checked = false;
            navMob.style.transition = "opacity 0.0s";
            navMob.style.display = "none";
            navMob.style.opacity = "0";
            navMob.style.height = "0";
            mobileLogoNav.style.display = "inline-block";

            for (let i = 0; i < mobileItems.length; i++) {
                mobileItems[i].style.height = "0";
                mobileItems[i].style.margin = "0";
                mobileItems[i].style.overflow = "hidden";
            }
        }
    }

    // function to show mobile navigation if checkbox is checked
    function showMobileNav () {
        checkBox.checked = true;
        navMob.style.display = "block";
        navMob.style.transition = "opacity 0.8s";
        navMob.style.opacity = "1";
        navMob.style.height = "auto";
        mobileLogoNav.style.display = "none";


        for (var i = 0; i < mobileItems.length; i++) {
            mobileItems[i].style.height = "auto";
            mobileItems[i].style.margin = "5px";
            mobileItems[i].style.overflow = "auto";
        }
    }


    //get current file name, if the filename is "inloggen.php", don't track left, right and bottom sidebar
    var fileName = location.pathname.split("/").slice(-1);
    if (fileName == "inloggen.php" || fileName == "bestemmingen.php" || fileName == "privacyverklaring.php") {
        // don't show mobile nav if you clicked on the footer or on the main area
        document.getElementById("main").onclick = noShowMobileNav;
        document.getElementById("footer").onclick = noShowMobileNav;
    } else {
        // don't show mobile nav if you clicked on the footer or on the main area
        document.getElementById("left-sidebar").onclick = noShowMobileNav;
        document.getElementById("right-sidebar").onclick = noShowMobileNav;
        document.getElementById("bottom-sidebar").onclick = noShowMobileNav;
        document.getElementById("main").onclick = noShowMobileNav;
        document.getElementById("footer").onclick = noShowMobileNav;
    }

    // show or don't show menu depending on checkbox
    checkBox.onclick = function() {
    if (checkBox.checked === true) {
        showMobileNav();
    } else if (checkBox.checked === false) {
        noShowMobileNav();
    }
}

if (checkBox.checked === true) {
    showMobileNav();
} else {
    noShowMobileNav();
}

window.onload = noShowMobileNav();