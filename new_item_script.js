var states = {
    item_name : false,
    item_desc : false,
    item_price : false  
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

    $('#item_name').on('blur', function () {
        var item_name = $('#item_name').val();
        updateLabel("item_name", this, item_name != '','This field is required') ;
    });

    $('#item_desc').on('blur', function () {
        var item_desc = $('#item_desc').val();
        updateLabel("item_desc", this, item_desc != '','This field is required');
    });

    $('#item_price').on('blur', function () {
        var item_price = $('#item_price').val();
        updateLabel("item_price", this, item_price != '','This field is required');
    });
    $('#reg_btn').on('click', function (e) {
        e.preventDefault();
        var iname = $('#item_name').val();
        var idesc = $('#item_desc').val();
        var iprice = $('#item_price').val();
        var cat_type = $('#category_type').val(); 
        var res_id = $('#res_id').attr("value");
        console.log(cat_type);       
        if (!checkState()) {
            $('#error_msg').text('');
        } else {
            // proceed with form submission
            $.ajax({
                url: 'new_item_insert.php',
                type: 'post',
                data: {
                    'save': 1,
                    'iname': iname,
                    'idesc': idesc,
                    'iprice': iprice,
                    'cat_type': cat_type,
                    'res_id' : res_id,                    
                },
                success: function (response) {
                    console.log(response);
                    if (response.msg == "success") {
                        alert('Added item successfully redirecting to restaurant page');
                        window.location = "details.php?id="+res_id;
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