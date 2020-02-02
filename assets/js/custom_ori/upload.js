    var save_method; //for save method string

    var table;

      $(document).ready(function() {

        var employee_id = $('#table_structural_training_history').attr('rel');

        table = $('#table_structural_training_history').DataTable({ 

          

          "processing": true, //Feature control the processing indicator.

          "serverSide": true, //Feature control DataTables' server-side processing mode.

          

          // Load data for the table's content from an Ajax source

          "ajax": {

              "url": "kepegawaian/structural_training_history/ajax_list",

              "type": "POST"

          },



          //Set column definition initialisation properties.

          "columnDefs": [

          { 

            "targets": [ -1 ], //last column

            "orderable": false, //set not orderable

          },

          ],



        });

      });



      // greating //

      function greatSuccess() {



        $.gritter.add({

               title: 'SUKSES!',

               text: 'Data berhasil diproses.',

               sticky: false,

               time: '1000',

               class_name: 'gritter-success'

        })



      }



    function greatError() {

        $.gritter.add({

             title: 'PERINGATAN!',

             text: 'Anda tidak memiliki hak akses atau terjadi kesalahan dalam proses.',

             sticky: false,

             time: '1000',

             class_name: 'gritter-error'

        })



    }

    function edit(id)

    {

      $("html, body").animate({ scrollTop: "600px" });

      $('#tab_sub_profil').empty();

      $('#tab_sub_profil').load('kepegawaian/structural_training_history/form/' + id + '?_=' + (new Date()).getTime());



    }



    function backlist()

    {

      $("html, body").animate({ scrollTop: "600px" });

      $('#tab_sub_profil').empty();

      $('#tab_sub_profil').load('kepegawaian/structural_training_history'+ '?_=' + (new Date()).getTime());



    }



    function add()

    {

      $("html, body").animate({ scrollTop: "600px" });

      $('#tab_sub_profil').empty();

      $('#tab_sub_profil').load('kepegawaian/structural_training_history/form/'+ '?_=' + (new Date()).getTime());



    }



    function structure()

    {

      $("html, body").animate({ scrollTop: "600px" });

      $('#tab_sub_profil').empty();

      $('#tab_sub_profil').load('kepegawaian/structural_training_history/structure/'+ '?_=' + (new Date()).getTime());



    }



    function reload_table()

    {

      table.ajax.reload(null,false); //reload datatable ajax 

    }



    function delete_structural_training_history(id)

    {



      if(confirm('Apakah anda yakin akan menghapus data ini?'))

      {

        // ajax delete data to database

          $.ajax({

            url : "kepegawaian/structural_training_history/ajax_delete/"+id,

            type: "POST",

            dataType: "JSON",

            success: function(data)

            {

               //if success reload ajax table

               greatSuccess();

               reload_table();

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                greatError();

            }

        });

         

      }



    }





$.validator.setDefaults({

  submitHandler: function() { 

    

    var formData = new FormData($('#form_structural_training_history')[0]);

    formData.append('fupload', $('input[type=file]')[0].files[0]); 

     url = "kepegawaian/structural_training_history/ajax_add";



       // ajax adding data to database

          $.ajax({

            url : url,

            type: "POST",

            data: formData,

            dataType: "JSON",

            contentType: false,

            processData: false,

            

            success: function(data, textStatus, jqXHR, responseText)

            { 



              greatSuccess();

              $('#btnSave').hide();

              $('#btnReset').hide();

              $('#btnAdd').show();

              //$('input[type="text"],texatrea, select', this).val('');

              //$('#form_work_unit').resetForm();

            },

            error: function (jqXHR, textStatus, errorThrown)

            {



              greatError();



            }

        });



  }



});



function disabledBtn()

  {

    $('#btnSave').disabled = true;

    return true;

  }





// jquery validation //

$().ready(function() {



  // validate signup form on keyup and submit

  $("#form_structural_training_history").validate({

    rules: {

      

      esth_name: "required",

      esth_institution: "required",

      esth_location: "required",

      esth_duration: {

        required: true,

        number: true

      },

      active: "required"

    },



    messages: {

      esth_name: "Masukan Nama Diklat!",

      esth_institution: "Masukkan Institusi!",

      esth_location: "Masukkan Lokasi Diklat!",

      esth_duration: {

        required: "Masukan Durasi Diklat!",

        number: "Durasi Diklat dengan angka!"

      },

    }

    

  });





});





// jquery choosen //

jQuery(function($) {

      

      if(!ace.vars['touch']) {

        $('.chosen-select').chosen({allow_single_deselect:true}); 

        //resize the chosen on window resize

    

        $(window)

        .off('resize.chosen')

        .on('resize.chosen', function() {

          $('.chosen-select').each(function() {

             var $this = $(this);

             $this.next().css({'width': $this.parent().width()});

          })

        }).trigger('resize.chosen');

        //resize chosen on sidebar collapse/expand

        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {

          if(event_name != 'sidebar_collapsed') return;

          $('.chosen-select').each(function() {

             var $this = $(this);

             $this.next().css({'width': $this.parent().width()});

          })

        });

    

      }

    

    

    });