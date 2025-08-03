// Update the input validation function to remove error on input/change
function validateInput(input) {
    var value = $.trim(input.val());
    var isOptional = input.attr("optional") === "true";
    var attrname = input.attr("name");

    input.on("input change", function() {
        input.removeClass("is-invalid");
        input.next(".invalid-feedback").remove();
    });

    if (!attrname) {
        console.warn("Input element is missing the name attribute.");
        return true;
    }

    attrname = attrname.replace(/\[\]/g, "");
    attrname = attrname.split("_").join(" ");
    attrname = attrname.charAt(0).toUpperCase() + attrname.slice(1);

    if (value === "" && !isOptional) {
        input.addClass("is-invalid");

        if (!input.next(".invalid-feedback").length) {
            input.after(
                '<p class="invalid-feedback">' +
                attrname +
                " field is required.</p>"
            );
        }
        return false;
    } else {
        input.removeClass("is-invalid");
        input.next(".invalid-feedback").remove();
        return true;
    }
}

// Update select validation function to remove error on change
function validateSelect(select) {
    var value = $.trim(select.val());
    var isOptional = select.attr("optional") === "true";
    var attrname = select.attr("name");

    select.on("change", function() {
        select.removeClass("is-invalid");
        select.next(".invalid-feedback").remove();
    });

    if (!attrname) {
        console.warn("Select element is missing the name attribute.");
        return true;
    }
    attrname = attrname.replace(/\[\]/g, "");
    attrname = attrname.split("_").join(" ");
    attrname = attrname.charAt(0).toUpperCase() + attrname.slice(1);

    if (value === "" && !isOptional) {
        select.addClass("is-invalid");

        if (!select.next(".invalid-feedback").length) {
            select.after(
                '<p class="invalid-feedback">' +
                attrname +
                " field is required.</p>"
            );
        }
        return false;
    } else {
        select.removeClass("is-invalid");
        select.next(".invalid-feedback").remove();
        return true;
    }
}

// Update radio/checkbox validation function to remove error on change
function validateRadioOrCheckbox(input) {
    var name = input.attr("name");
    var isOptional = input.attr("optional") === "true";

    input.on("change", function() {
        input.removeClass("is-invalid");
        input.next(".invalid-feedback").remove();
    });

    if (!name) {
        console.warn("Radio/Checkbox element is missing the name attribute.");
        return true;
    }

    if ($("input[name='" + name + "']:checked").length === 0 && !isOptional) {
        var attrname = name.split("_").join(" ");
        attrname = attrname.charAt(0).toUpperCase() + attrname.slice(1);
        input.addClass("is-invalid");

        if (!input.next(".invalid-feedback").length) {
            input.after(
                '<p class="invalid-feedback">' +
                attrname +
                " field is required.</p>"
            );
        }
        return false;
    } else {
        input.removeClass("is-invalid");
        input.next(".invalid-feedback").remove();
        return true;
    }
}

// Update file input validation function to remove error on change
function validateFile(fileInput) {
    var isOptional = fileInput.attr("optional") === "true";
    var attrname = fileInput.attr("name");

    fileInput.on("change", function() {
        fileInput.removeClass("is-invalid");
        fileInput.next(".invalid-feedback").remove();
    });

    if (!attrname) {
        console.warn("File input element is missing the name attribute.");
        return true;
    }

    var file = fileInput[0].files[0];
    attrname = attrname.split("_").join(" ");
    attrname = attrname.charAt(0).toUpperCase() + attrname.slice(1);

    if (!file && !isOptional) {
        fileInput.addClass("is-invalid");

        if (!fileInput.next(".invalid-feedback").length) {
            fileInput.after(
                '<p class="invalid-feedback">' +
                attrname +
                " field is required.</p>"
            );
        }
        return false;
    } else {
        fileInput.removeClass("is-invalid");
        fileInput.next(".invalid-feedback").remove();
        return true;
    }
}

// Function for form validation and submission using Ajax
function SubmitForm(formId, callback) {
    var isTrue = true;
    var form = $("#" + formId);

    // var submitButton = form.find("button[type='submit']");  // Find the submit button
    // // Disable the submit button to prevent double submission
    // submitButton.prop('disabled', true);

    // Loop through each input, select, textarea, and file element within the form
    form.find("input, select, textarea, file").each(function() {
        var element = $(this);

        if (
            element.is(
                "input[type='text'],input[type='email'],input[type='number'],input[type='password'],input[type='time'],input[type='date'], textarea"
            )
        ) {
            if (!validateInput(element)) {
                isTrue = false;
            }
        } else if (element.is("select")) {
            if (!validateSelect(element)) {
                isTrue = false;
            }
        } else if (element.is("input[type='file']")) {
            if (!validateFile(element)) {
                isTrue = false;
            }
        } else if (
            element.is("input[type='radio']") ||
            element.is("input[type='checkbox']")
        ) {
            if (!validateRadioOrCheckbox(element)) {
                isTrue = false;
            }
        }
    });

    // Submit the form via Ajax if all inputs, selects, textareas, radio buttons, checkboxes, and files are valid
    if (isTrue) {
        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: new FormData(form[0]),
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#overlayer").show();
            },
            success: function(data) {
                // console.log(data);
                $("#overlayer").hide();
                if (data.status == true) {
                    if (typeof callback === "function") {
                        callback(data);
                    }
                } else {
                    handleBackendErrors(data.message);
                }
            },
            error: function(_xhr, status, error) {
                console.error("Error submitting the form:", error);
                console.error("Status submitting the form:", status);
            },
        });
    }

    // Handle backend validation errors and display them to the user
    function handleBackendErrors(errors) {
        for (var fieldName in errors) {
            var errorMessages = errors[fieldName];
            var inputElement = form.find('[name="' + fieldName + '"]');
            if (inputElement.length > 0) {
                // Clear any existing error messages
                inputElement.removeClass("is-invalid");
                inputElement.next(".invalid-feedback").remove();

                // Display the new error message next to the input element
                for (var i = 0; i < errorMessages.length; i++) {
                    var errorMessage = errorMessages[i];
                    inputElement.addClass("is-invalid");
                    inputElement.after(
                        '<p class="invalid-feedback">' + errorMessage + "</p>"
                    );
                }
            }
        }
    }
}

// Message function
function msg(data) {
    var message = data.message;
    if (data.status == true) {
        toastr.success(message);
    } else {
        toastr.error(message);
    }
}

// Log out function
function logout(target_page, redirect_page) {
    swal({
        title: "Are you sure want to sign out?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: target_page,
                beforeSend: function() {
                    $("#overlayer").show();
                },
                success: function(response) {
                    $("#overlayer").hide();
                    if (response.status) {
                        swal(
                            "Good job!",
                            "Logged Out Successfully.",
                            "success"
                        ).then((value) => {
                            if (value == true) {
                                window.location = redirect_page;
                            }
                        });
                    } else {
                        swal("Oops!", "Log Out Failed.", "error");
                    }
                },
            });
        }
    });
}

//! Delete Function
function DeleteRecord(id, url, callback) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: url,
                type: "post",
                data: { id: id },
                beforeSend: function() {
                    $("#overlayer").show();
                },
                success: function(data) {
                    // console.log(data);
                    $("#overlayer").hide();
                    if (typeof callback === "function") {
                        callback(data);
                    }
                },
            });
        }
    });
}
