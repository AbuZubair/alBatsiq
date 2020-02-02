    // greating //
    function greatSuccess() {

        $.gritter.add({
               title: 'SUKSES!',
               text: 'Data berhasil diproses.',
               sticky: false,
               time: '1500',
               class_name: 'gritter-success'
        })

      }

    function greatError() {
        $.gritter.add({
             title: 'PERINGATAN!',
             text: 'Anda tidak memiliki hak akses atau terjadi kesalahan dalam proses.',
             sticky: false,
             time: '1500',
             class_name: 'gritter-error'
        })

    }


    $.validator.setDefaults({
      submitHandler: function() { 
        
         url_app = "pengaturan/aplikasi/proses_app";

           // ajax adding data to database
              $.ajax({
                url : url_app,
                type: "POST",
                data: $('#form_app').serialize(),
                dataType: "JSON",
                success: function(data)
                { 

                  greatSuccess();

                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                  greatError();

                }
            });

      }

    });

    /*$.validator.setDefaults({
      submitHandler: function() { 
        
         url = "pengaturan/aplikasi/proses_author";

           // ajax adding data to database
              $.ajax({
                url : url,
                type: "POST",
                data: $('#form_author').serialize(),
                dataType: "JSON",
                success: function(data)
                { 

                  greatSuccess();

                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                  greatError();

                }
            });

      }

    });*/

    function disabledBtn()
      {
        $('#btnSave').disabled = true;
        $('#btnSaveAuth').disabled = true;
        return true;
      }


    // jquery validation //
    $().ready(function() {

    // validate signup form on keyup and submit
    
    $("#form_app").validate({
      rules: {
        
        app_name: {
          required: true,
          maxlength: 255
        },
        header_title: {
          required: true,
          maxlength: 255
        },
        text_footer: {
          required: true,
          maxlength: 255
        },
        copyright: {
          required: true,
          maxlength: 30
        },
        description: {
          required: true,
          maxlength: 255
        },
        auth_name: {
          required: true,
          maxlength: 30
        },
        company_name: {
          required: true,
          maxlength: 30
        }
      },

      messages: {
        app_name: {
          required: "Masukan Applcation Name!",
          maxlength: "Applcation Name harus diisi maksimal 255 karaketer"
        },
        header_title: {
          required: "Masukan Header Title!",
          maxlength: "Header Title harus diisi maksimal 255 karaketer"
        },
        text_footer: {
          required: "Masukan Text Footer!",
          maxlength: "Text Footer harus diisi maksimal 255 karaketer"
        },
        copyright: {
          required: "Masukan Copyright!",
          maxlength: "Copyright harus diisi maksimal 30 karaketer"
        },
        description: {
          required: "Masukan Description!",
          maxlength: "Description harus diisi maksimal 255 karaketer"
        },
        auth_name: {
          required: "Masukan Author Name!",
          maxlength: "Author Name harus diisi maksimal 30 karaketer"
        },
        company_name: {
          required: "Masukan Company Name!",
          maxlength: "Company Name harus diisi maksimal 30 karaketer"
        },
        
      }
    });



  });

   