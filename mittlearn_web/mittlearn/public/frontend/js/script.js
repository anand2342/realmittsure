if (localStorage.getItem('activeTab') == 'academic') {
    localStorage.removeItem('activeTab');
}



let cartIcon = `${base_url}frontend/images/cart-icon.svg`;
let cartIconSaved = `${base_url}frontend/images/cart-icon-saved.svg`;

let wishlistIcon = `${base_url}frontend/images/heart-icon.svg`;
let wishlistIconSaved = `${base_url}frontend/images/red-heart-icon.svg`;

// Main scripts
function generateSessionId() {
    let sessionId = localStorage.getItem("user_session_id") || "";
    if (!sessionId) {
        sessionId = `${new Date().getTime()}${Math.random()
            .toString(36)
            .substring(2, 12)
            .toUpperCase()}`;
        localStorage.setItem("user_session_id", sessionId);
    }
    globalVar.sessionId = sessionId;
}
generateSessionId();



// pass guest_user_id in hidden input guestUserId

// $(document).ready(function () {
//     const schoolSearchInput = $("#schoolNameSearch");
//     const schoolSelect = $("#schoolName");
//     const schoolSearchResults = $("#schoolSearchResults");
//     const classSelectWrapper = $("#classSelectWrapper");
//     const classSelect = $("#className");

//     // Get schools data from the hidden element
//     const schoolsElement = document.getElementById("schools-data");
//     const schools = JSON.parse(schoolsElement.dataset.schools);

//     // School search functionality
//     schoolSearchInput.on("input", function () {
//         const query = $(this).val().toLowerCase().trim();
//         schoolSearchResults.empty().hide();

//         if (query === "") {
//             return;
//         }

//         const filteredSchools = schools.filter((school) =>
//             school.name.toLowerCase().includes(query)
//         );

//         if (filteredSchools.length > 0) {
//             schoolSearchResults.show();
//             filteredSchools.forEach((school) => {
//                 const resultItem = $(
//                     '<a href="#" class="list-group-item list-group-item-action  "></a>'
//                 )
//                     .text(school.name)
//                     .click(function (e) {
//                         e.preventDefault();
//                         schoolSearchInput.val(school.name);
//                         schoolSelect.val(school.id).trigger("change");
//                         schoolSearchResults.hide();
//                     });
//                 schoolSearchResults.append(resultItem);
//             });
//         }
//     });

//     // When search input loses focus, consider it as a new school if not selected from list
//     schoolSearchInput.on("blur", function () {
//         setTimeout(() => {
//             if (schoolSelect.val() === "" && schoolSearchInput.val() !== "") {
//                 // Treat as new school
//                 schoolSelect
//                     .val("new")
//                     .data("custom-name", schoolSearchInput.val());
//                 classSelectWrapper.show();
//                 fetchClasses("all", classSelect); // Show all classes for new schools
//             }
//         }, 200); // Small delay to allow click events on search results to process
//     });

//     // Original school select change handler
//     schoolSelect.change(function () {
//         const selectedSchool = $(this).val();

//         if (selectedSchool) {
//             classSelectWrapper.show();
//             if (selectedSchool === "new") {
//                 fetchClasses("all", classSelect); // Show all classes for new schools
//             } else {
//                 fetchClasses(selectedSchool, classSelect);
//             }
//         } else {
//             classSelectWrapper.hide();
//         }
//     });

//     // Class change handler
//     classSelect.change(function () {
//         const selectedSchool = schoolSelect.val();
//         const selectedClass = $(this).val();

//         if (selectedSchool != null && selectedClass) {
//             fetchSeries(selectedSchool, selectedClass, $("#seriesName"));
//         }
//         fetchSeries(selectedSchool, selectedClass, $("#seriesName"));

//     });

//     // When form is submitted, handle the custom school name
//     $("form").submit(function (e) {
//         if (schoolSelect.val() === "new") {
//             // Create a hidden input for the custom school name
//             $("<input>")
//                 .attr({
//                     type: "hidden",
//                     name: "customSchoolName",
//                     value: schoolSearchInput.val(),
//                 })
//                 .appendTo("form");
//         }
//     });

//     // Original functions
//     function fetchClasses(schoolId, classSelect) {
//         $.ajax({
//             url: "/get-classes",
//             method: "GET",
//             data: { school_id: schoolId },
//             success: function (response) {
//                 classSelect
//                     .empty()
//                     .append(
//                         '<option value="" disabled selected>Select Class</option>'
//                     );

//                 if (
//                     response.classes &&
//                     Object.keys(response.classes).length > 0
//                 ) {
//                     Object.entries(response.classes).forEach(function ([
//                         id,
//                         name,
//                     ]) {
//                         classSelect.append(
//                             '<option value="' + id + '">' + name + "</option>"
//                         );
//                     });
//                 } else {
//                     classSelect.append(
//                         '<option value="" disabled>No classes available</option>'
//                     );
//                 }
//             },
//             error: function () {
//                 alert("Error fetching classes");
//             },
//         });
//     }

//     function fetchSeries(schoolId, classId, seriesSelect) {
//         $.ajax({
//             url: "/get-series",
//             method: "GET",
//             data: { school_id: schoolId, class_id: classId },
//             success: function (response) {
//                 seriesSelect
//                     .empty()
//                     .append(
//                         '<option value="" disabled selected>Select Series</option>'
//                     );

//                 if (
//                     response.series &&
//                     Object.keys(response.series).length > 0
//                 ) {
//                     Object.entries(response.series).forEach(function ([
//                         id,
//                         name,
//                     ]) {
//                         seriesSelect.append(
//                             '<option value="' + id + '">' + name + "</option>"
//                         );
//                     });
//                 } else {
//                     seriesSelect.append(
//                         '<option value="" disabled>No series available</option>'
//                     );
//                 }
//             },
//             error: function () {
//                 alert("Error fetching series");
//             },
//         });
//     }
// });

const guestUserElement = document.getElementById("guestUserId");
if (guestUserElement) {
    guestUserElement.value = globalVar.sessionId;
}

$(document).on("click", ".add-to-cart-btn", function (e) {
    e.preventDefault();
    // Add to cart script
    const guestUserSessionId = globalVar.sessionId;

    const user_id = $("#userAuthId").val();
    const cart_id = $("#savedCartId").val();
    const item_id = $(this).data("item-id");
    const item_type = $(this).data("item-type");
    const course_id = $(this).data("course-id");
    const course_full_price = $(this).data("course-full-price");
    const course_price = $(this).data("course-price");
    const $icon = $(".cart-icon-" + course_id);
    const currentIconSrc = $icon.attr("src");

    const isAddedToCart = currentIconSrc === cartIconSaved;

    const action = isAddedToCart ? "remove" : "add";

    const post_data = {
        cart_id: cart_id,
        user_id: user_id,
        guest_user_id: guestUserSessionId,
        item_type: item_type,
        item_id: item_id,
        course_id: course_id,
        action: action,
        quantity: 1,
        course_full_price: course_full_price,
        price: course_price,
        discount: "",
        coupon_code: "",
        added_at: new Date().toISOString(),
        status: action === "add" ? "active" : "inactive",
        created_by_admin: "",
    };
    const endPointUrl =
        action === "add" ? "api/item-add-to-cart" : "api/item-remove-from-cart";

    globalFunc.ajaxCall(
        endPointUrl,
        post_data,
        "POST",
        globalFunc.before,
        globalFunc.handleToggleCartSuccess,
        globalFunc.error,
        globalFunc.complete
    );
});

$(document).on("click", ".wishlistButton", function (e) {
    e.preventDefault();
    // Add to Wishlist script
    const guestUserSessionId = globalVar.sessionId;

    const user_id = $("#userAuthId").val();
    const wishlist_id = $("#savedWishlistId").val();
    const item_id = $(this).data("item-id");
    const item_type = $(this).data("item-type");
    const course_id = $(this).data("course-id");
    const $icon = $(".wishlist-icon-" + course_id);
    const currentIconSrc = $icon.attr("src");

    const isAddedToWishlist = currentIconSrc === wishlistIconSaved;

    const action = isAddedToWishlist ? "remove" : "add";

    const post_data = {
        wishlist_id: wishlist_id,
        user_id: user_id,
        guest_user_id: guestUserSessionId,
        item_type: item_type,
        item_id: item_id,
        course_id: course_id,
        action: action,
        quantity: 1,
        added_at: new Date().toISOString(),
        status: action === "add" ? "active" : "inactive",
        created_by_admin: "",
    };

    const endPointUrl =
        action === "add"
            ? "api/item-add-to-wishlist"
            : "api/item-remove-from-wishlist";

    globalFunc.ajaxCall(
        endPointUrl,
        post_data,
        "POST",
        globalFunc.before,
        globalFunc.handleToggleWishlistSuccess,
        globalFunc.error,
        globalFunc.complete
    );
});

globalFunc.ajaxCall = function (
    path,
    post_data,
    call_type,
    b_send = null,
    success = null,
    error = null,
    complete = null
) {
    $.ajax({
        url: base_url + path,
        data: post_data,
        type: call_type,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        beforeSend: b_send,
        success: success,
        error: error,
        complete: complete,
    });
};

globalFunc.before = function () {
    $("#loader").show();
};

globalFunc.error = function (xhr) {
    $("#loader").hide(); // Hide loader

    // Extract the error message from responseJSON if it exists
    if (xhr.responseJSON && xhr.responseJSON.message) {
        const errorMessage = xhr.responseJSON.message; // Get the message from the response
        $("#info").html(errorMessage);
        toastr.error(errorMessage, "Error", {
            positionClass: "toast-top-right",
            timeOut: 3000,
        });
    } else {
        // Fallback to a default message if no specific message is found
        const defaultMessage =
            "An unexpected error occurred. Please try again.";
        $("#info").html(defaultMessage);
        toastr.error(defaultMessage, "Error", {
            positionClass: "toast-top-right",
            timeOut: 3000,
        });
    }

    // Log the full error object for debugging
    console.log("Error:", xhr);
};

globalFunc.complete = function () {
    $("#loader").hide();
};

globalFunc.handleToggleCartSuccess = function (data) {
    const course_id = data.data.cart.course_id;
    // Determine success message and icon based on action type
    if (data.status === "success") {
        if (data.data.cart.status === "active") {
            $("#info").html("Item successfully added to cart!");
            $(`.cart-icon-${course_id}`).attr("src", cartIconSaved);
            $(`.add-to-cart-btn[data-course-id="${course_id}"]`).addClass(
                "cartAdded"
            );
            document.getElementById("savedCartId").value = data.data.cart.id;
            toastr.success("Item successfully added to cart!", "Success", {
                positionClass: "toast-top-right",
                timeOut: 3000,
            });

            if (
                data.data.plan_packs &&
                data.data.plan_packs.status === "success"
            ) {
                const { discount, remaining_courses } = data.data.plan_packs;

                // Set the discount message based on type
                const discountMessage =
                    remaining_courses === 0
                        ? `
                        <span><b>Woohoo! </b></span>
                        <p class='w-100'> You got extra <span><b>${
                            discount.type === "flat"
                                ? `₹${discount.value}`
                                : `${discount.value}%`
                        }</b></span> off on your order.</p>`
                        : `
                        <span>Get <b>${
                            discount.type === "flat"
                                ? `₹${discount.value}`
                                : `${discount.value}%`
                        }</b> Discount</span>
                        <p class='w-100'>To avail this offer, add ${remaining_courses} more items.</p>`;

                // Update the message and show the div
                $("#planPackMsg")
                    .html(discountMessage)
                    .toggle(remaining_courses !== null);
            } else {
                // Clear and hide the div if no discount
                $("#planPackMsg").html("").hide();
            }
        } else if (data.data.cart.status === "cancelled") {
            $("#info").html("Item successfully removed from cart!");
            $(`.cart-icon-${course_id}`).attr("src", cartIcon);
            $(`.crtBtn[data-course-id="${course_id}"]`).removeClass(
                "cartAdded"
            );
            document.getElementById("savedCartId").value = data.data.cart.id;
            toastr.success("Item successfully removed from cart!", "Success", {
                positionClass: "toast-top-right",
                timeOut: 3000,
            });
            $("#planPackMsg").html("");
        }
    } else {
        // Default error message if no specific message in the response
        $("#info").html("Failed to update cart.");
        toastr.error("Failed to update cart. Please try again.", "Error", {
            positionClass: "toast-top-right",
            timeOut: 3000,
        });
    }
};
globalFunc.handleToggleWishlistSuccess = function (data) {
    const course_id = data.data.wishlist.course_id;
    // Determine success message and icon based on action type
    if (data.status === "success") {
        if (data.data.wishlist.status === "active") {
            $("#info").html("Item successfully added to wishlist!");
            $(`.wishlist-icon-${course_id}`).attr("src", wishlistIconSaved);
            document.getElementById("savedWishlistId").value =
                data.data.wishlist.id;
            toastr.success("Item successfully added to wishlist!", "Success", {
                positionClass: "toast-top-right",
                timeOut: 3000,
            });
        } else if (data.data.wishlist.status === "inactive") {
            $("#info").html("Item successfully removed from wishlist!");
            $(`.wishlist-icon-${course_id}`).attr("src", wishlistIcon);
            document.getElementById("savedWishlistId").value =
                data.data.wishlist.id;
            toastr.success(
                "Item successfully removed from wishlist!",
                "Success",
                {
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                }
            );
        }
    } else {
        // Default error message if no specific message in the response
        $("#info").html("Failed to update wishlist.");
        toastr.error("Failed to update wishlist. Please try again.", "Error", {
            positionClass: "toast-top-right",
            timeOut: 3000,
        });
    }
};

// Navbar toggler
$(".navbar-toggler").click(function () {
    $("#navbarContent").toggleClass("show");
});

// Scroll event for header
$(window).scroll(function () {
    var sc = $(window).scrollTop();
    if (sc > 30) {
        $(".mainHeader").addClass("fixed");
    } else {
        $(".mainHeader").removeClass("fixed");
    }
});

$(document).ready(function () {
    initTabs();
    initSlick();
});
$(document).ready(function () {
    initTabs();
    initSlick();
});

$(document).on("change", "#switchnonacademic", function () {
    if (this.checked) {
        localStorage.setItem("activeTab", "nonacademic");
        $("#nonacademic-tab").click(); // trigger tab switch
        $(this).prop("checked", false); // reset toggle
    }
});

$(document).on("change", "#switchacademic", function () {
    if (this.checked) {
        localStorage.setItem("activeTab", "academic");
        $("#academic-tab").click(); // trigger tab switch
        $(this).prop("checked", false); // reset toggle
    }
});

// Function to toggle content and initialize sliders
function initTabs() {
    const activeTab = localStorage.getItem("activeTab") || "nonacademic"; // Default to nonacademic if nothing is stored

    if (activeTab === "nonacademic") {
        $("#nonacademic-tab").addClass("active"); // Show non-academic tab
        $("#academic-tab").removeClass("active");
        $("#nonacademic-tab-pane").addClass("show active");
        $("#academic-tab-pane").removeClass("show active");
    } else {
        $("#academic-tab").addClass("active"); // Show academic tab
        $("#nonacademic-tab").removeClass("active");
        $("#academic-tab-pane").addClass("show active");
        $("#nonacademic-tab-pane").removeClass("show active");
    }

    toggleContent(activeTab);
}

// Function to toggle content visibility and initialize the slider
function toggleContent(activeTab) {
    if (activeTab === "nonacademic") {
        $(".nonacademic-page").show();
        $(".academic-page").hide();
        $(".nonacademic-page .slick-slider").slick("refresh");

        // Show explore button on the inactive (academic) tab
        $("#academic-tab .explore-btn").show();
        $("#nonacademic-tab .explore-btn").hide();

        // Show non-academic banner, hide academic banner
        $("#homeBannerNonAcademic").show();
        $("#homeBannerAcademic").hide();

        // ✅ Fix layout issue with Slick after tab switch
        $("#homeBannerNonAcademic .slick-slider").slick("setPosition");
    } else {
        $(".academic-page").show();
        $(".nonacademic-page").hide();
        $(".academic-page .slick-slider").slick("refresh");

        // Show explore button on the inactive (nonacademic) tab
        $("#nonacademic-tab .explore-btn").show();
        $("#academic-tab .explore-btn").hide();

        // Show academic banner, hide non-academic banner
        $("#homeBannerAcademic").show();
        $("#homeBannerNonAcademic").hide();

        // ✅ Fix layout issue with Slick after tab switch
        $("#homeBannerAcademic .slick-slider").slick("setPosition");
    }

    localStorage.setItem("activeTab", activeTab);
}

// Event listener for tab changes
$(".tabLink").on("click", function (e) {
    const activeTab =
        $(this).attr("id") === "nonacademic-tab" ? "nonacademic" : "nonacademic";
        // $(this).attr("id") === "nonacademic-tab" ? "nonacademic" : "academic";
    localStorage.setItem("activeTab", activeTab);
    toggleContent(activeTab);
});

function initSlick() {
    // Check and initialize only if the class exists
    if ($(".bannerSlide1").length) {
        $(".bannerSlide1").slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            autoplay: true,
            autoplaySpeed: 2000,
            arrows: false,
            cssEase: "linear",
        });
    }
    if ($(".bannerSlideNonAcadmic").length) {
        $(".bannerSlideNonAcadmic").slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            autoplay: true,
            autoplaySpeed: 2000,
            arrows: false,
            cssEase: "linear",
        });
    }

    if ($(".slider-content").length) {
        $(".slider-content").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            infinite: false,
            asNavFor: ".slider-thumb",
            prevArrow:
                '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow:
                '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
            responsive: [
                {
                    breakpoint: 991,
                },
                {
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                    },
                },
            ],
        });
    }

    if ($(".slider-thumb").length) {
        $(".slider-thumb").slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            asNavFor: ".slider-content",
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            infinite: false,
        });
    }

    if ($(".recentSearch").length) {
        $(".recentSearch").slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            margin: 10,
            dots: false,
            centerMode: false,
            focusOnSelect: true,
        });
    }

    if ($(".featureContent").length) {
        $(".featureContent").slick({
            vertical: true,
            verticalSwiping: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 0,
            speed: 2000,
            cssEase: "linear",
            infinite: true,
            arrows: false,
            touchMove: true,
            swipeToSlide: true,
            swipe: true,
            responsive: [
                {
                    breakpoint: 991,
                },
                {
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                        slidesToShow: 1,
                        speed: 4000,
                    },
                },
            ],
        });
    }

    if ($(".slider-explore").length) {
        $(".slider-explore").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            infinite: false,
            asNavFor: ".slider-explore-thumb",
            prevArrow:
                '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow:
                '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
        });
    }

    if ($(".slider-explore-thumb").length) {
        $(".slider-explore-thumb").slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: false,
            asNavFor: ".slider-explore",
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            infinite: false,
        });
    }

    if ($(".meetSlider").length) {
        $(".meetSlider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            infinite: true,
            asNavFor: ".meetSliderThumb",
            autoplay: true,
        });
    }

    if ($(".meetSliderThumb").length) {
        $(".meetSliderThumb").slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            asNavFor: ".meetSlider",
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            arrows: false,
            infinite: true,
            autoplay: true,
        });
    }

    if ($(".sayAboutSlider").length) {
        $(".sayAboutSlider").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            centerMode: true,
            arrows: true,
            variableWidth: true,
            infinite: true,
            focusOnSelect: true,
            touchMove: true,
            responsive: [
                {
                    breakpoint: 991,
                },
                {
                    breakpoint: 767,
                    settings: {
                        centerMode: false,
                        variableWidth: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }
}

// password validation msg
if ($("#reset-password-form")[0] != undefined) {
    $("#reset-password-form").validate({
        rules: {
            password: { required: true, minlength: 8 },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password",
            },
        },
    });
}
if (typeof jQuery.validator !== "undefined") {
    // Add a custom method for regex validation
    jQuery.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            return this.optional(element) || new RegExp(regexp).test(value);
        },
        "Invalid format."
    );

    // Initialize validation
    if ($("#register-password-form")[0] != undefined) {
        $("#register-password-form").validate({
            rules: {
                name: { required: true },
                access_code: { required: true },
                email: { email: true },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                },
                password: {
                    minlength: 8,
                    regex: /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, // Custom regex method
                },
                password_confirmation: {
                    minlength: 8,
                    equalTo: "#password",
                },
                terms_accepted: { checked: true, required: true },
            },
            messages: {
                password: {
                    regex: "Must include a letter, number, and special character.",
                },
            },
        });
    }
}

$(".eye_icon").click(function () {
    const id = $(this).data("id");
    const $icon = $(this).find("i"); // Select the Bootstrap icon

    if ("password" == $("#" + id).attr("type")) {
        $("#" + id).prop("type", "text");
        $icon.removeClass("bi-eye-slash").addClass("bi-eye"); // Change to 'eye-slash'
    } else {
        $("#" + id).prop("type", "password");
        $icon.removeClass("bi-eye").addClass("bi-eye-slash"); // Revert to 'eye'
    }
});

// Ensure messages to auto-close after 5 seconds.
document.addEventListener("DOMContentLoaded", function () {
    // Set a timeout to remove the alert after 5 seconds (5000 ms)
    setTimeout(function () {
        let alerts = document.querySelectorAll(".alert");
        alerts.forEach((alert) => {
            alert.classList.remove("show");
            alert.classList.add("fade");
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000); // 5 seconds
});
if ($("#languageEducationSection")[0] != undefined) {
    // Array of text and corresponding image sources
    const contentData = [
        {
            text: "German",
            image: `${base_url}frontend/images/location-img1.jpg`,
        },
        {
            text: "French",
            image: `${base_url}frontend/images/location-img2.jpg`,
        },
        {
            text: "Spanish",
            image: `${base_url}frontend/images/location-img3.jpg`,
        },
        {
            text: "Sanskrit",
            image: `${base_url}frontend/images/location-img4.jpg`,
        },
    ];

    let currentIndex = 0;
    let rotationDegree = 0;

    const textElement = document.getElementById("dynamicText");
    const circleImage = document.getElementById("circleImage");
    const locationImage = document.getElementById("locationImage");

    // Function to update the text, image, and rotation
    function updateContent() {
        textElement.innerHTML = contentData[currentIndex].text;
        locationImage.src = contentData[currentIndex].image;

        rotationDegree += 90;
        circleImage.style.transform = `rotate(${rotationDegree}deg)`;

        currentIndex = (currentIndex + 1) % contentData.length;
    }
    // Update content every 1 second
    setInterval(updateContent, 3000);
    // Initial content load
    updateContent();
}
// Blog search script
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        // Check if the element exists
        searchInput.addEventListener("input", function () {
            const query = this.value.toLowerCase();
            const blogs = document.querySelectorAll(".blog-item");

            blogs.forEach((blog) => {
                const title = blog.getAttribute("data-title").toLowerCase();
                const metaTitle = blog
                    .getAttribute("data-meta-title")
                    .toLowerCase();
                const metaKeywords = blog
                    .getAttribute("data-meta-keywords")
                    .toLowerCase();
                const metaDescription = blog
                    .getAttribute("data-meta-description")
                    .toLowerCase();
                const body = blog.getAttribute("data-body").toLowerCase();

                // Check if the query matches any of the data attributes
                if (
                    title.includes(query) ||
                    metaTitle.includes(query) ||
                    metaDescription.includes(query) ||
                    metaKeywords.includes(query) ||
                    body.includes(query)
                ) {
                    blog.style.display = "block"; // Show the blog
                } else {
                    blog.style.display = "none"; // Hide the blog
                }
            });
        });
    }
});

$(function () {
    $(".sliderBlog")
        .on("init", function (event, slick) {
            $(this).append(
                '<div class="slick-counter"><span class="current"></span> / <span class="total"></span></div>'
            );
            $(".current").text(slick.currentSlide + 1);
            $(".total").text(slick.slideCount);
        })
        .slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            autoplay: false,
            arrows: true,
            slidesToScroll: 1,
            prevArrow:
                '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-chevron-left"></i></button>',
            nextArrow:
                '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-chevron-right"></i></button>',
        })
        .on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $(".current").text(nextSlide + 1);
        });
});

function confirmDelete(url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00438C",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

if (
    globalVar.page === "content_upload" ||
    globalVar.page === "content_folder_view" ||
    globalVar.page === "my_course_access_code"
) {
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                const query = this.value.toLowerCase();
                const classItems = document.querySelectorAll(".class-item");

                classItems.forEach((item) => {
                    const title = item.getAttribute("data-title").toLowerCase();

                    if (title.includes(query)) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        }
    });
}
if (globalVar.page === "my_course_access_code") {
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const tableRows = document.querySelectorAll("#search-results tr");

        if (searchInput) {
            searchInput.addEventListener("input", function () {
                const query = this.value.toLowerCase();

                tableRows.forEach((row) => {
                    const title = row.getAttribute("data-title").toLowerCase();

                    if (title.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }
    });
}

// track user progrres function script
function setVideoDuration(videoId, courseId, chapterId) {
    let video = document.getElementById(`video-${videoId}`);
    let duration = Math.floor(video.duration);

    fetch("/save-user-video-duration", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf_token,
        },
        body: JSON.stringify({
            video_id: videoId,
            video_duration: duration,
            course_id: courseId,
            chapter_id: chapterId,
        }),
    });
}

let lastSentTime = {};
let completedVideos = new Set();

function updateProgress(videoId) {
    let video = document.getElementById(`video-${videoId}`);

    video.addEventListener("timeupdate", function () {
        if (completedVideos.has(videoId)) {
            // If video is marked as completed, do not send further updates
            return;
        }

        let currentTime = Math.floor(video.currentTime); // Get current time in seconds
        let lastUpdate = lastSentTime[videoId] || 0;

        if (currentTime - lastUpdate >= 1) {
            // Ensure at least 1 seconds have passed
            $.ajax({
                url: `/update-user-video-progress`,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                },
                contentType: "application/json",
                data: JSON.stringify({
                    video_id: videoId,
                    watched_duration: currentTime,
                }),
                success: function (response) {
                    // If server response indicates video is fully watched, stop further updates
                    if (
                        response.message ===
                        "Video already completed, progress not updated"
                    ) {
                        completedVideos.add(videoId);
                    }
                },
                error: function (error) {
                    console.error("Error updating watch time:", error);
                },
            });

            lastSentTime[videoId] = currentTime; // Update last sent time
        }
    });
}
function showPurchaseMessage(index, videoId) {
    if (index >= 3) {
        // Show motivational message after the third video
        let messageDiv = document.getElementById("purchaseMessage-" + videoId);
        messageDiv.style.display = "block";

        // Optionally, hide the button to prevent further clicks
        let button = event.target;
        button.disabled = true; // Disable the button
        button.innerHTML = "Please purchase to unlock more!";
    }
}

//  Change playback speed function
//   function changePlaybackSpeed(videoId) {
//       let video = document.getElementById(`video-${videoId}`);
//       let speed = document.getElementById(`playback-speed-${videoId}`).value;
//       video.playbackRate = parseFloat(speed);
//   }
//   function changeVideoQuality(videoId) {
//       let video = document.getElementById(`video-${videoId}`);
//       let quality = document.getElementById(`video-quality-${videoId}`).value;
//       let source = video.querySelector('source');

//       // Change the video URL based on quality (assuming different URLs for HD/SD exist)
//       if (quality === 'HD') {
//           source.src = "{{ $video->signed_url }}"; // Default HD URL
//       } else {
//           source.src = "{{ $video->sd_signed_url }}"; // Alternative SD URL (You need to store this in DB)
//       }

//       video.load(); // Reload video with new quality
//   }
