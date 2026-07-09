document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("vallidateName");
    if (nameInput) {
        nameInput.addEventListener("input", function () {
            clearTimeout(this.delayTimer);
            this.delayTimer = setTimeout(() => {
                const name = this.value;
                const errorMessage =
                    name.length < 3
                        ? "Name must be at least 3 characters."
                        : "";
                const errorElement =
                    document.getElementById("vallidateNameError");
                errorElement.textContent = errorMessage;
                errorElement.style.display = errorMessage ? "block" : "none";
            }, 1000);
        });
    }

    const titleInput = document.getElementById("vallidateTitle");
    if (titleInput) {
        titleInput.addEventListener("input", function () {
            clearTimeout(this.delayTimer);
            this.delayTimer = setTimeout(() => {
                const name = this.value;
                const errorMessage =
                    name.length < 3
                        ? "Ttile must be at least 3 characters."
                        : "";
                const errorElement =
                    document.getElementById("vallidateTitleError");
                errorElement.textContent = errorMessage;
                errorElement.style.display = errorMessage ? "block" : "none";
            }, 1000);
        });
    }

  
});
