jQuery(function ($) {
    "use strict";

    //preloader
    $(window).ready(function () {
        $("#preloader").delay(100).fadeOut("fade");
    });

    //1. data background
    $("[data-background]").each(function () {
        var $data_bg = $(this).attr("data-background");
        $(this).css({
            "background-image": "url(" + $data_bg + ")",
        });
    });

    //2. Scroll to Top
    $(window).on("scroll", function () {
        let scrollbarPosition = $(this).scrollTop();
        if (scrollbarPosition > 150) {
            $(".scroll-top-btn").addClass("active");
        } else {
            $(".scroll-top-btn").removeClass("active");
        }
    });
    $(".scroll-top-btn").on("click", function () {
        $("body,html").animate({
            scrollTop: 0,
        });
    });

    //3.sticky header
    $(window).on("scroll", function () {
        let scrollbarPosition = $(this).scrollTop();
        if (scrollbarPosition > 100) {
            $(".header-sticky").addClass("sticky-on");
        } else {
            $(".header-sticky").removeClass("sticky-on");
        }
    });

    //5. check password
    $(".check-password").each(function () {
        var eyeIcon = $(this).find(".eye-icon");
        eyeIcon.on("click", function () {
            $(this).hide();
            $(this).next().show();
            $(this).siblings("input[type='password']").attr("type", "text");
        });
        var eyeSlash = $(this).find(".eye-slash");
        eyeSlash.on("click", function () {
            $(this).hide();
            $(this).prev().show();
            $(this).siblings("input[type='text']").attr("type", "password");
        });
    });

    //6. category dropdown
    $(".category-dropdown-btn").on("click", function () {
        $(this).siblings(".category-dropdown-box").toggleClass("active");
    });
    $(document).on("mouseup", function (e) {
        var categoryDropdownBox = $(".category-dropdown");
        if (
            !categoryDropdownBox.is(e.target) &&
            categoryDropdownBox.has(e.target).length === 0
        ) {
            $(".category-dropdown-box").removeClass("active");
        }
    });

    //7.offcanvas menu
    function offCanvas() {
        $(".offcanvas-toggle").on("click", function () {
            $(".offcanvas_menu").addClass("active");
        });
        $(".offcanvas-close").on("click", function () {
            $(".offcanvas_menu").removeClass("active");
        });
        $(document).on("mouseup", function (e) {
            var offCanvasMenu = $(".offcanvas_menu");
            if (
                !offCanvasMenu.is(e.target) &&
                offCanvasMenu.has(e.target).length === 0
            ) {
                $(".offcanvas_menu").removeClass("active");
            }
        });
    }
    offCanvas();

    //mobile menu
    $(".mobile-menu-toggle").on("click", function () {
        $(".offcanvas-left-menu").addClass("active");
    });
    $(".offcanvas-left-menu .offcanvas-close").on("click", function () {
        $(".offcanvas-left-menu").removeClass("active");
    });
    $(document).on("mouseup", function (e) {
        var offCanvasMenu = $(".offcanvas-left-menu");
        if (
            !offCanvasMenu.is(e.target) &&
            offCanvasMenu.has(e.target).length === 0
        ) {
            $(".offcanvas-left-menu").removeClass("active");
        }
    });
    $(".mobile-menu ul li.has-submenu a").each(function () {
        $(this).on("click", function () {
            $(this).siblings("ul").slideToggle();
            $(this).toggleClass("icon-rotate");
        });
    });

    //simple bar
    Array.from(document.querySelectorAll(".scrollbar")).forEach(
        (el) =>
            new SimpleBar(el, {
                autoHide: false,
                classNames: {
                    // defaults
                    content: "simplebar-content",
                    scrollContent: "simplebar-scroll-content",
                    scrollbar: "simplebar-scrollbar",
                    track: "simplebar-track",
                },
            })
    );

    //file upload
    $(".file-upload").each(function () {
        var FileInput = $(this).children("input");
        var FileNameOutput = $(this).children(".file-name");
        FileInput.on("change", function () {
            var FileName = this.files[0].name;
            FileNameOutput.text(FileName);
            console.log($(this));
        });
    });

    //  dark light mood
    function updateLogoByTheme() {
        var theme = document
            .querySelector(":root")
            .getAttribute("data-bs-theme");
        if (theme === "dark") {
            $(".logo-light").addClass("d-none");
            $(".logo-dark").removeClass("d-none");
        } else {
            $(".logo-dark").addClass("d-none");
            $(".logo-light").removeClass("d-none");
        }
    }

    function updateLogoByTheme() {
        var theme = document
            .querySelector(":root")
            .getAttribute("data-bs-theme");
        if (theme === "dark") {
            $(".logo-light").addClass("d-none");
            $(".logo-dark").removeClass("d-none");
        } else {
            $(".logo-dark").addClass("d-none");
            $(".logo-light").removeClass("d-none");
        }
    }

    var setDarkMode = (active = false) => {
        var wrapper = document.querySelector(":root");
        if (active) {
            wrapper.setAttribute("data-bs-theme", "dark");
            localStorage.setItem("theme", "dark");
        } else {
            wrapper.setAttribute("data-bs-theme", "light");
            localStorage.setItem("theme", "light");
        }

        updateLogoByTheme();
    };
    var toggleDarkMode = () => {
        var theme = document
            .querySelector(":root")
            .getAttribute("data-bs-theme");
        // If the current theme is "light", we want to activate dark
        setDarkMode(theme === "light");
    };
    var initDarkMode = () => {
        var theme = localStorage.getItem("theme");
        if (theme == "dark") {
            setDarkMode(true);
        } else {
            setDarkMode(false);
        }
        var toggleButton = document.querySelector(".tt-theme-toggle");
        toggleButton && toggleButton.addEventListener("click", toggleDarkMode);
    };
    initDarkMode();
    updateLogoByTheme();
});
