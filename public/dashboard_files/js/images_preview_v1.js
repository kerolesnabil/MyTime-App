function avatar_img_preview(input_file_id, img_holder_id, img_style_func_name, image_id) {
    $(input_file_id).on('change', function () {
        if (this.files) {
            let image_holder = $(img_holder_id);
            let img_id = $('#'+image_id);

            console.log(img_id);
            console.log(image_id);


            let reader = new FileReader();


            reader.onload = function () {
                $(img_id).remove();

                $("<img />", {
                    "src": this.result,
                    "class": img_style_func_name,
                    "id": image_id
                }).appendTo(image_holder);
            };
            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);

        }
        else {
            alert("This browser does not support FileReader.");
        }
    });

}


function images_previews(input_file_id, holder_img_id, image_id, func_style_img, func_style_input_text, type_file = null, input_file_slider_name = null, slider_counter_id = null) {

    // holder_img_id ==> div will hold img
    // type_file ( slider or image )
    // func_style_img ==> css func name for image or slider
    // func_style_input_text=> col-md-3 pr-md-1 or col-md-6 pr-md-1
    // object_name_has_slider ==> $place
    $("#"+input_file_id).on('change', function () {

        if (this.files) {
            let count_of_files = this.files.length;
            let img_holder = $("#"+holder_img_id);
            let count_current_files;
            let img_id = $('#'+image_id);



            if(type_file === 'slider'){


                count_current_files = parseInt($("#"+slider_counter_id).val());  //count slider images
                count_of_files = count_of_files + count_current_files;

                console.log('count_current_files => ' + count_current_files);

                for( let i = count_current_files ; i < count_of_files; i++) {

                    let reader = new FileReader();
                    reader.onload = function () {

                        let row_div = $("<div class='row mb-3'>", {
                        });

                        let image = $("<img />", {
                            "src": this.result,
                            "class": func_style_img,
                        });

                        let img_div = $("<div class='col-md-2 pr-md-1'>", {
                            class: func_style_input_text,
                        });
                        image.appendTo(img_div);
                        img_div.appendTo(row_div);

                        /******* generate Title && Alt inputs ********/

                        let label_title = $("<label>", {
                            "text": 'Title',
                        });

                        let input_title = $("<input>", {
                            "type": 'text',
                            "placeholder": "Image Title",
                            "name": `data[${input_file_slider_name}][title][${i}]`,
                            "class": "form-control"
                        });

                        let title = $('<div class="col-md-2 pr-md-1">', {
                            class: func_style_input_text,
                        });
                        label_title.appendTo(title);
                        input_title.appendTo(title);
                        title.appendTo(row_div);

                        let label_alt = $("<label>", {
                            "text": 'Alt',
                        });
                        let input_alt = $("<input>", {
                            "type": 'text',
                            "placeholder": "Image Alt",
                            "name": `data[${input_file_slider_name}][alt][${i}]`,
                            "class": "form-control"
                        });
                        let alt = $('<div class="col-md-2 pr-md-1">', {
                            class: func_style_input_text,
                        });
                        label_alt.appendTo(alt);
                        input_alt.appendTo(alt);
                        alt.appendTo(row_div);
                        row_div.appendTo(img_holder);

                        let delete_btn = $("<input>", {
                            type: 'button',
                            class: "btn btn-danger float-right remover_div",
                            value : "Delete",
                            onclick: 'remover_slider_img(this)'
                        });
                        let delete_div = $('<div class="col-md-2 pr-md-1 place_slider_img_delete_btn">', {
                            class: func_style_input_text,
                        });
                        delete_btn.appendTo(delete_div);
                        delete_div.appendTo(row_div);
                        row_div.appendTo(img_holder);

                    };
                    img_holder.show();
                    reader.readAsDataURL(this.files[i - count_current_files]);
                }
            }
            else {
                count_current_files = 0;
                
                for( let i = count_current_files ; i < count_of_files; i++) {
                    let reader = new FileReader();
                    reader.onload = function () {
                        $(img_id).remove();
                        console.log(img_id);


                        let image = $("<img />", {
                            "src": this.result,
                            "class": func_style_img ,
                            "id": image_id,
                        });
                        image.appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL(this.files[i - count_current_files]);
                }
            }
        }
        else {
            alert("This browser does not support FileReader.");
        }
    });
}

function remover_slider_img(element) {


    let parent_div = element.parentNode.parentNode;
    let deleted_value = parent_div.id;

    if(deleted_value.length === 0) {
        parent_div.remove();
    }
    else {
        $('<input>').attr({
            type: 'hidden',
            name: 'data[slider_img][deleted_urls][]',
            value: deleted_value,
        }).appendTo($('#deleted_slider_imgs'));
        parent_div.remove();
    }
}

function say_hello_modal(modal_id) {

    let id_of_model = $('#'+modal_id);
    // Check if user saw the modal
    let key = 'hadSayHelloModal',
        hadModal = localStorage.getItem(key);

    // Show the modal only if new user
    if (!hadModal) {
        id_of_model.modal('show');
        setTimeout(function () {id_of_model.modal('hide');}, 3000);
    }

    // If modal is displayed, store that in localStorage
    id_of_model.on('shown.bs.modal', function () {
        localStorage.setItem(key, true);
    });
}


