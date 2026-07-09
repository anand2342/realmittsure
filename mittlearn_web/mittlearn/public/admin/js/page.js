if (globalVar.page == 'email_template') {
    $(document).ready(function () {
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link', 'image']
                ]
            }
        });

        var initialContent = globalVar.emailTemplateBody;
        quill.root.innerHTML = initialContent;

        $('form').on('submit', function (event) {
            const quillContent = quill.root.innerHTML.trim();
            $('#editor-content').val(quillContent);

            if (!quillContent) {
                event.preventDefault();
                alert('The body field is required.');
            }
        });

        if ($('#floatingSelect').val() !== "") {
            loadConstants();
        }
        $('#floatingSelect').on('change', function () {
            loadConstants();
        });

        function loadConstants() {
            const constant = $('#floatingSelect').val();
            console.log('Selected action:', constant);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: "{{ URL::to('admin/email-template/get-constant') }}",
                type: "POST",
                data: {
                    constant
                },
                dataType: 'json',
                success: function (response) {
                    const $constants = $('#constants');
                    $constants.empty().append(
                        '<option value="" disabled selected>-- Select Constant --</option>');

                    if (response && response.length) {
                        $.each(response, function (index, text) {
                            $constants.append(`<option value="${text}">${text}</option>`);
                        });
                    } else {
                        alert('No constants available for this action.');
                    }
                },
                error: function (xhr) {
                    console.error('Error fetching constants:', xhr.responseText);
                    alert('Failed to fetch constants. Please try again.');
                }
            });
        }

        $('#insertVariableBtn').on('click', function () {
            const selectedConstant = $('#constants').val();
            insertHtml(selectedConstant);
        });

        function insertHtml(selectedConstant) {
            if (selectedConstant) {
                const newStr = `{${selectedConstant}}`;
                const selection = quill.getSelection();
                const index = selection ? selection.index : quill.getLength();
                quill.insertText(index, newStr);
                quill.setSelection(index + newStr.length);
            } else {
                console.error('No constant selected to insert.');
            }
        }

        quill.on('text-change', function () {
            $('#editor-content').val(quill.root.innerHTML.trim());
        });
    });
}

//
$(document).on('click', '.delete_btn', function () {
    var deleteUrl = $(this).data('url');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function (result) {
        if (result.value) {
            window.location.href = deleteUrl;
        }
    });
});


// //
// $(document).on('click', '.info button', function (e) {
//     $('#info').html('');
//     const post_data = { id: $(this).data('id'), date: globalVar.date };
//     globalFunc.ajaxCall('api/get-list', post_data, 'POST', globalFunc.before, globalFunc.infoList, globalFunc.error, globalFunc.complete);
// });

// globalFunc.infoList = function (data) {
//     if (Object.keys(data.list).length > 0) {
//         var k = 1;
//         $.each(data.list, function (index, val) {

//         });
//     } else {
//         addNoRoomTr();
//     }
// }


$(document).on('click', '.add-to-cart-btn', function (e) {
    e.preventDefault();
    console.log(globalFunc); // Check if globalFunc is defined
    console.log(typeof globalFunc.ajaxCall);
    $('#info').html(''); // Clear any existing info messages

    // Set the data to be sent in the POST request
    const post_data = {
        user_id: '', // Provide user ID if logged in, or leave blank
        session_id: '', // Assign session ID if needed
        item_type: 'course',
        item_id: $(this).data('id'), // Assuming data-id holds item ID
        quantity: 1,
        price: 100, // Set item price dynamically if needed
        discount: '', // Provide discount value if available
        coupon_code: '', // Provide coupon code if available
        added_at: new Date().toISOString(), // Current date-time
        status: 'active',
        created_by_admin: ''
    };

    // API endpoint for adding to cart
    const apiUrl = 'api/add-to-cart';

    // Call the AJAX function with the required parameters
    globalFunc.ajaxCall(
        apiUrl,
        post_data,
        'POST',
        globalFunc.before,
        globalFunc.handleAddToCartSuccess,
        globalFunc.error,
        globalFunc.complete
    );
});
globalFunc.ajaxCall = function (path, post_data, call_type, b_send = null, success = null, error = null, complete = null) {
    $.ajax({
        url: base_url + "" + path,
        data: post_data,
        type: call_type,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        beforeSend: b_send,
        success: success,
        error: error,
        complete: complete
    })
};
globalFunc.before = function () { $('#loader').show() };
globalFunc.error = function () { $('#loader').hide() };
globalFunc.complete = function () { $('#loader').hide() };

// Success handler function for AJAX call
globalFunc.handleAddToCartSuccess = function (data) {
    if (data.success) {
        $('#info').html('Item successfully added to cart!'); // Show success message

        // Change cart icon color to blue on success
        $('.add-to-cart-btn[data-id="' + data.item_id + '"] .cart-icon').css(
            'filter',
            'brightness(0) saturate(100%) invert(23%) sepia(75%) saturate(2496%) hue-rotate(190deg) brightness(94%) contrast(89%)'
        );
    } else {
        $('#info').html('Failed to add item to cart.'); // Show error message
    }
};

if (globalVar.page == 'state-city') {
    $('#state-select').on('change', function () {
        var stateId = $(this).val();
        $('#city-select').html('<option value="">Select</option>');
        if (stateId) {
            var url = "{{ route('sp.getCities', ':state') }}".replace(':state', stateId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    if (data && Object.keys(data).length > 0) {
                        $.each(data, function (id, name) {
                            $('#city-select').append('<option value="' + id +
                                '">' + name + '</option>');
                        });
                    } else {
                        $('#city-select').html(
                            '<option value="">No cities available</option>');
                    }
                },
            });
        }
    });
}





if (globalVar.page == 'holiday') {
    document.addEventListener('DOMContentLoaded', function () {
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');
        const dayInput = document.getElementById('day');

        function calculateDays() {
            const fromDate = new Date(fromDateInput.value);
            const toDate = new Date(toDateInput.value);

            if (!isNaN(fromDate) && !isNaN(toDate) && toDate >= fromDate) {
                const timeDifference = toDate - fromDate;
                const days = Math.ceil(timeDifference / (1000 * 60 * 60 * 24)) + 1; // Including both dates
                dayInput.value = days;
            } else {
                dayInput.value = ''; // Clear the input if dates are invalid
            }
        }

        if (fromDateInput && toDateInput) {
            fromDateInput.addEventListener('change', calculateDays);
            toDateInput.addEventListener('change', calculateDays);
        }
    });
    $(document).ready(function () {
        const selectElement = $("#state-select");

        // Initialize Select2
        selectElement.select2({
            closeOnSelect: false,
            placeholder: "--Select--",
            allowClear: true,
        });

        // Handle the "All Select" functionality
        selectElement.on("select2:select", function (e) {
            const selectedOption = e.params.data.id;

            if (selectedOption === "all") {
                // When "All Select" is chosen, select all options (excluding "All Select" itself)
                selectElement.find("option").prop("selected", true); // Mark all options as selected
                selectElement.trigger("change"); // Update the UI
            }
        });

        selectElement.on("select2:unselect", function (e) {
            const unselectedOption = e.params.data.id;

            if (unselectedOption === "all") {
                // When "All Select" is unchosen, deselect all options
                selectElement.val(null).trigger("change"); // Remove all selections
            }
        });

        // Monitor the 'change' event to log selected options
        selectElement.on("change", function () {
            const selectedValues = $(this).val();
            console.log("Currently selected options:", selectedValues);
        });

        // Handle "All Select" manually to select/deselect everything including "All Select" itself
        selectElement.on("select2:open", function () {
            var allSelected = selectElement.val();

            // If "All Select" is selected, select all options
            if (allSelected && allSelected.includes("all")) {
                selectElement.find('option').each(function () {
                    $(this).prop('selected', true);
                });
                selectElement.trigger("change"); // Ensure the UI is updated
            }
        });
    });
}

// VIew per page record setting script
document.addEventListener('DOMContentLoaded', function () {
    const paginationSelect = document.getElementById('paginationSelectOnpage');

    if (paginationSelect) {
        paginationSelect.addEventListener('change', function () {
            const perPage = this.value;

            fetch("/admin/set-pagination", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ per_page: perPage })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        });
    }
});
