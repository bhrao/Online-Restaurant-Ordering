var states = {
    restaurant_name : true,
    street : true,
    zip : true,
    ph_no : true,    
    price_range : true,
    rating : true,
    delivery_time : true
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

    $('#restaurant_name').on('blur', function () {
        var restaurant_name = $('#restaurant_name').val();
        updateLabel("restaurant_name", this, restaurant_name != '','This field is required') ;
    });

    $('#street').on('blur', function () {
        var street = $('#street').val();
        updateLabel("street", this, street != '','This field is required');
    });

    $('#zip').on('blur', function () {        
        var zip = $('#zip').val();
        updateLabel("zip", this, zip != '','This field is required');
    });

    $('#ph_no').on('blur', function () {
        var res_id = $('#res_id').attr("value");
        var ph_no = $('#ph_no').val();
        var el = this;
        if (ph_no == '') {
            updateLabel("ph_no", this, false,'This field is required');
        } else if ($('#ph_no').val().length != 10) {
            updateLabel("ph_no", this, false,'Please enter a 10 digit number');
        } else {
            $.ajax({
                url: 'edit_restaurant_insert.php',
                type: 'post',
                data: {
                    'ph_no_check': 1,
                    'ph_no': ph_no,
                    'res_id': res_id,
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

    $('#price_range').on('blur', function () {
        var price_range = $('#price_range').val();
        updateLabel("price_range", this, price_range != '','This field is required');
    });

    $('#rating').on('blur', function () {
        var rating = $('#rating').val();
        updateLabel("rating", this, rating != '','This field is required');
    });

    $('#delivery_time').on('blur', function () {
        var delivery_time = $('#delivery_time').val();
        updateLabel("delivery_time", this, delivery_time != '','This field is required');
    });

    $(".a-ayg").click(function () {
        $('#restaurant_form').submit();
    })

    $('#restaurant_form').on('submit', function (e) {
        if (checkState()) {
            return;
        }
        $('#error_msg').text('Please fill all fields');
        e.preventDefault();
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