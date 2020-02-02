    var save_method; //for save method string
    var table;
      $(document).ready(function() {
        table = $('#dynamic-table').DataTable({ 
          
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "reference/m_kabupaten/ajax_list",
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
      $('#page-area-content').load('reference/m_kabupaten/form/' + id + '?_=' + (new Date()).getTime());

    }

    function backlist()
    {
    
      $('#page-area-content').html(loading);
      $('#page-area-content').load('reference/m_kabupaten'+ '?_=' + (new Date()).getTime());

    }

    function add()
    {
      $('#page-area-content').html(loading);
      $('#page-area-content').load('reference/m_kabupaten/form/'+ '?_=' + (new Date()).getTime());

    }

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

    function delete_kabupaten(id)
    {

      if(confirm('Apakah anda yakin akan menghapus data ini?'))
      {
        // ajax delete data to database
          $.ajax({
            url : "reference/m_kabupaten/ajax_delete/"+id,
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
    
     url = "reference/m_kabupaten/ajax_add";

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form_kabupaten').serialize(),
            dataType: "JSON",
            success: function(data)
            { 

              greatSuccess();
              $('#btnSave').hide();
              $('#btnReset').hide();
              $('#btnAdd').show();
              //$('input[type="text"],texatrea, select', this).val('');
              //$('#form_m_kabupaten').resetForm();
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
  $("#form_kabupaten").validate({
    rules: {
      
      id: "required",
      id_provinsi: "required",
      nama_kabupaten: "required",
      active: "required"
    },

    messages: {
      id: "Masukan ID Kabupaten!",
      id_provinsi: "Masukan ID Provinsi!",
      nama_kabupaten: "Masukan Nama Provinsi !"
    }
  });


});
