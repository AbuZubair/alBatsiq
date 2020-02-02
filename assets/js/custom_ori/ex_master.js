    var save_method; //for save method string
    var table;
      $(document).ready(function() {
        table = $('#dynamic-table').DataTable({ 
          
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "ex_master/ajax_list",
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
    function edit(id)
    {
    
      $('#page-area-content').html(loading);
      $('#page-area-content').load('ex_master/form/' + id + '?_=' + (new Date()).getTime());

    }

    function backlist()
    {
    
      $('#page-area-content').html(loading);
      $('#page-area-content').load('ex_master'+ '?_=' + (new Date()).getTime());

    }

    function add()
    {
      $('#page-area-content').html(loading);
      $('#page-area-content').load('ex_master/form/'+ '?_=' + (new Date()).getTime());

    }

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

    function delete_ex_master(id)
    {

      if(confirm('Apakah anda yakin akan menghapus data ini?'))
      {
        // ajax delete data to database
          $.ajax({
            url : "ex_master/ajax_delete/"+id,
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
      
    var formData = new FormData($('#form_ex_master')[0]);

    formData.append('fupload', $('input[type=file]')[0].files[0]); 

    url = "ex_master/ajax_add";

       // ajax adding data to database
          $.ajax({

            url : url,

            type: "POST",

            data: formData,

            dataType: "JSON",

            contentType: false,

            processData: false,
            /*
            url : url,
            type: "POST",
            data: $('#form_ex_master').serialize(),
            dataType: "JSON",*/
            
            success: function(data)
            { 

              greatSuccess();
              $('#btnSave').hide();
              $('#btnReset').hide();
              $('#btnAdd').show();
              //$('input[type="text"],texatrea, select', this).val('');
              //$('#form_ex_master').resetForm();
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
  $("#form_ex_master").validate({
    rules: {
      
      ex_master_name: "required",
      active: "required"
    },

    messages: {
      ex_master_name: "Masukan Nama Ex Master!"
    }
  });


});
