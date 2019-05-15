var states = {
    first_name : false,
    last_name : false,
    ph_no : false,
    email : false,
    password: false,
    confirm_password : false
};


$(document).ready(function () {
    $('._381fS').on("focus", function () {
        $(this).siblings("label").addClass('_2tL9P');
    });
    $('._381fS').on("blur", function () {
        if (this.value == "") {
            $(this).siblings("label").removeClass('_2tL9P');
        }
    });

    function updateLabel(field, el, val, msg){
        states[field] = val;
        if(val) {
            $(el).siblings("label").removeClass("A7Y41");
            $(el).siblings("label").children("span").text('');
        }
        else{
            $(el).siblings("label").addClass("A7Y41");
            $(el).siblings("label").children("span").text(msg);
        }
    }

    $('#first_name').on('change', function () {
        var first_name = $('#first_name').val();
        updateLabel("first_name", this, first_name != '','This field is required') ;
    });

    $('#last_name').on('change', function () {
        var last_name = $('#last_name').val();
        updateLabel("last_name", this, last_name != '','This field is required');
    });

    $('#ph_no').on('blur', function () {
        var ph_no = $('#ph_no').val();
        var el = this;
        if (ph_no == '') {
            updateLabel("ph_no", this, false,'This field is required');
        } else if ($('#ph_no').val().length != 10) {
            updateLabel("ph_no", this, false,'Please enter a 10 digit number');
        } else {
            $.ajax({
                url: 'register.php',
                type: 'post',
                data: {
                    'ph_no_check': 1,
                    'ph_no': ph_no,
                },
                success: function (response) {

                    if (response.msg == 'taken') {
                        updateLabel("ph_no", el, false,'This phone number alreay exists');
                    } else if (response.msg == 'not_taken') {
                        updateLabel("ph_no", el, true);
                    }
                }
            });
        }
    });

    $('#email').on('blur', function () {
        var email = $('#email').val();
        var el = this;
        if (email == '') {
            updateLabel("email", this, false, 'This field is required');
        } else if (IsEmail(email) == false) {
            updateLabel("email", this, false, 'Enter a valid email');
        } else {
            $.ajax({
                url: 'register.php',
                type: 'post',
                data: {
                    'email_check': 1,
                    'email': email,
                },
                success: function (response) {
                    if (response.msg == 'taken') {
                        updateLabel("email", el, false, 'This Email already taken');
                    } else if (response.msg == 'not_taken') {
                        updateLabel("email", el, true);
                    }
                }
            });
        }
    });

    $('#a_password').on('change', function () {
        var password = $('#a_password').val();
        if (password == '') {
            updateLabel("password", this, false, 'This field is required');
        } else if ($('#a_password').val().length < 8) {
            updateLabel("password", this, false, 'Password should be atleat 8 characters long');
        } else {
            updateLabel("password", this, true);
        }
    });

    $('#confirm_password').on('change', function () {
        var confirm_password = $('#confirm_password').val();
        if (confirm_password == '') {
            updateLabel("confirm_password", this, false, 'This field is required');
        } else if ($("#confirm_password").val() != $('#a_password').val()) {
            updateLabel("confirm_password", this, false, 'Password did not match');
        } else {
            updateLabel("confirm_password", this, true);
        }
    });

    $('#reg_btn').on('click', function (e) {
        e.preventDefault();
        var fname = $('#first_name').val();
        var lname = $('#last_name').val();
        var ph_no = $('#ph_no').val();
        var email = $('#email').val();
        var password = $('#a_password').val();
        if (!checkState()) {
            $('#error_msg').text('');
        } else {
            // proceed with form submission
            $.ajax({
                url: 'register.php',
                type: 'post',
                data: {
                    'save': 1,
                    'fname': fname,
                    'lname': lname,
                    'email': email,
                    'ph_no': ph_no,
                    'password': password,
                },
                success: function (response) {
                    console.log(response);
                    if (response.msg == "success") {
                        alert('Added user successfully redirecting to login page');
                        window.location = "index.php";
                    }

                }
            });
        }
    });
});

function checkState() {
    var flag = true;
    $.each(states, function(k,v){
        if(!v){
            flag = v;
            return v;
        }

    });
    return flag;
}

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}