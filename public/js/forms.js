//region open- close- Form()
    // open & close popup-form on button click
    function openForm() {
        if(document.getElementById("popupForm").style.display === "block") {
            document.getElementById("popupForm").style.display = "none";
        } else {
            document.getElementById("popupForm").style.display = "block";
        }
}

    function closeForm() {
        if (confirm("Weet je zeker dat je het formulier wilt resetten?")) {
            document.getElementById("popupForm").reset();
            document.getElementById("popupForm").style.display = "none";
        }
    }
//endregion

//region resetApplicationForm()
// reset application form on button click
    function resetApplicationForm() {
        if (confirm("Weet je zeker dat je het formulier wilt resetten?")) {
            document.getElementById("inschrijfform").reset();
            // scroll to top
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
    }
//endregion

//region calcHoogteRuim() - unused for now
// calculate total height in application form on change (used in bestemmingen.php)
    function calcHoogteRuim() {
        let hoogteRuim;
        let diepgang = document.inschrijfForm.diepgang.value;
        let hoogteBoven = document.inschrijfForm.hoogteBoven.value;

        // set value to 0 if the fields haven't been filled in yet
        if(document.inschrijfForm.diepgang.value.length === 0) {
            diepgang = 0;
        }

        if(document.inschrijfForm.hoogteBoven.value.length === 0) {
            hoogteBoven = 0;
        }

        // calculate the value of hoogteRuim
        diepgang = parseInt(diepgang, 10);
        hoogteBoven = parseInt(hoogteBoven, 10);
        hoogteRuim = diepgang + hoogteBoven;
        document.getElementById('totaleHoogte').value = hoogteRuim;
    }
//endregion