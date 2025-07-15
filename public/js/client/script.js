$(document).ready(function () {
    
    $('#AddClient').on('click', function(e) {
        e.preventDefault();

        let files = $('#PhotoCinClient')[0].files;

        if (files.length > 2) {
            new AWN().alert("Vous ne pouvez télécharger que 2 photos maximum", { durations: { alert: 5000 } });
            return; // إيقاف العملية
        }
        let formData = new FormData($('#FormAddClient')[0]);
        formData.append('_token', csrf_token);

        $('#AddClient').prop('disabled', true).text('Enregistrement...');

        $.ajax({
            type: "POST",
            url: AddClient,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('#AddClient').prop('disabled', false).text('Sauvegarder');

                if (response.status == 200) {
                    new AWN().success(response.message, { durations: { success: 5000 } });
                    $('#ModalAddClient').modal('hide');
                    $('.Table_Client').DataTable().ajax.reload();
                    $('#FormAddClient')[0].reset();
                } else if (response.status == 400) {
                    $('.ValidationAddClient').html("").addClass('alert alert-danger');
                    $.each(response.errors, function(key, error) {
                        $('.ValidationAddClient').append('<li>' + error + '</li>');
                    });
                    setTimeout(() => {
                        $('.ValidationAddClient').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else if (response.status == 404 || response.status == 500) {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function() {
                $('#AddClient').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    function phoneFormatter() {
        $('#PhoneClient, #PhoneClientEdit').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, ''); // إزالة أي أحرف غير رقمية
    
            if (number.length <= 10) {
                number = number.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, "$1-$2-$3-$4-$5");
            } else {
                number = number.substring(0, 10).replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, "$1-$2-$3-$4-$5");
            }
    
            $(this).val(number);
        });
    }
    
    
    $(phoneFormatter);

   /*  const inputElement = document.querySelector('input[type="file"]');

    // Create a FilePond instance
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
        server : 'upload/'
    }); */



    $(function ()
    {
        /* if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        } */
        initializeDataTable('.Table_Client', client);
        function initializeDataTable(selector, url)
        {
            var tableClient = $(selector).DataTable({
                dom: 'Bfrtip', // لإظهار الأزرار
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export excel',
                        className: 'btn btn-success',
                        filename: 'table client'
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    dataSrc: function (json) {
                        if (json.data.length === 0) {
                            $('.paging_full_numbers').css('display', 'none');
                        }
                        return json.data;
                    }
                },
                columns:
                [

                    {data: 'firstname'             , name: 'firstname'},
                    {data: 'lastname'              , name: 'lastname'},
                    {data: 'cin'                   , name: 'cin'},
                    {data: 'address'               , name: 'address'},
                    {data: 'phone'                 , name: 'phone'},
                    {
                        data: 'image_cin',
                        name: 'image_cin',
                        render: function(data, type, full, meta) {
                            if (data) {
                                // فك تشفير HTML entities
                                let decodedData = $('<textarea/>').html(data).text();
                    
                                try {
                                    let images = JSON.parse(decodedData);
                                    if (images.length > 0) {
                                        let imageTags = images.map((img, index) => 
                                            `<img src="${img}" width="50" height="50" class="thumb-md me-2 rounded-circle avatar-border" id="image_${full.id}" />`
                                        ).join('');
                                        return imageTags;
                                    }
                                } catch (e) {
                                    console.log("JSON Error:", e);
                                }
                            }
                            return `<span class="badge bg-warning">Aucune photo</span>`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {data: 'username'              , name: 'username'},
                    {data: 'created_at'            , name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}

                ],
                language: {
                    "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        }
                    }
                }
            });
            $(selector + ' tbody').on('click', '.editClient', function(e)
            {
                e.preventDefault();
                var IdcClient          = $(this).attr('data-id');
                var firstname          = $(this).closest('tr').find('td:eq(0)').text();
                var lastname           = $(this).closest('tr').find('td:eq(1)').text();
                var cin                = $(this).closest('tr').find('td:eq(2)').text();
                var address            = $(this).closest('tr').find('td:eq(3)').text();
                var phone              = $(this).closest('tr').find('td:eq(4)').text();
                
                $('#NomClientEdit').val(firstname);
                $('#PrenomClientEdit').val(lastname);
                $('#CinClientEdit').val(cin);
                $('#PhoneClientEdit').val(phone);
                $('#AddressClientEdit').val(address);
                
                $('#EditClient').attr('data-value',IdcClient);
                

            });

            $(selector + ' tbody').on('click','img',function(e)
            {
                e.preventDefault();
                let idimage = $(this).attr('id');
                let idclient = idimage.replace(/\D/g, ''); // استخراج الأرقام فقط

                $.ajax({
                    type: "get",
                    url: DisplayCIN,
                    data: {
                        id: idclient,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status == 200) {
                            let imageContainer = $('.ContentForPictureCinCustomer');
                            imageContainer.empty(); // مسح المحتوى السابق

                            let row = $('<div class="row"></div>'); // إنشاء صف

                            if (response.Data_Image.length == 1) {
                                let col = `
                                    <div class="col-sm-12 mb-3">
                                        <img src="${response.Data_Image[0]}" class="img-thumbnail rounded shadow w-100"/>
                                    </div>
                                `;
                                row.append(col);
                            } else {
                                response.Data_Image.forEach(function (img) {
                                    let col = `
                                        <div class="col-sm-12 col-md-12 col-xl-6 mb-3">
                                            <img src="${img}" class="img-thumbnail rounded shadow w-100"/>
                                        </div>
                                    `;
                                    row.append(col);
                                });
                            }

                            imageContainer.append(row); // إضافة الصف داخل العنصر

                            $('#ModalDisplayImageCIN').modal("show"); // عرض المودال
                        } else {
                            toastr.warning(response.message); // رسالة تحذير
                        }
                    }
                });

                
            });


            $(selector + ' tbody').on('click','.DisplayContract',function(e)
            {
                e.preventDefault();
                $('#ModalDisplayContract').modal("show");
                $('#idclient_contract').attr('value',$(this).attr('data-id')); 
                $('#idcompany_contract').attr('value',$(this).attr('data-company'));

                let idclient = $('#idclient_contract').val();
                let idcompany = $('#idcompany_contract').val();
                if (!idclient || !idcompany) {
                    new AWN().warning("Please enter both client and company ID.", {durations: {warning: 5000}})
                    return;
                }
                $.ajax({
                    type: "get",
                    url: DisplayContract,
                    data: 
                    {
                        id : $(this).attr('data-id'), // id = idclient
                    },
                    dataType: "json",
                    success: function (data) 
                    {
                        $('#contractsList').empty();

                        if (data.length === 0) {
                            $('#contractsList').append('<p>No contracts found.</p>');
                            return;
                        }

                        let row = $('<div class="row g-3"></div>'); // `g-3` adds spacing between columns

                        data.forEach(contract => {
                            if (!contract.iamge_contract) return;

                            let filePath = '/' + contract.iamge_contract;
                            let fileName = contract.iamge_contract.split('/').pop();
                            let fileType = fileName.toLowerCase().endsWith('.pdf') ? 'pdf' : 'image';

                            let html = `
                                <div class="col-md-4">
                                    <div class="card contract-card h-100 bg-light shadow-sm">
                                        <div class="card-body text-center">
                                            ${fileType === 'pdf' 
                                                ? `<div class="contract-icon display-3">📄</div>`
                                                : `<img src="${filePath}" class="contract-image img-fluid rounded" alt="Contract Image" style="max-height: 150px;">`}
                                            <h6 class="mt-3 text-truncate">${fileName}</h6>
                                            <a href="${filePath}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">Afficher</a>
                                            <a href="${filePath}" download class="btn btn-outline-secondary btn-sm mt-2 ms-2">Télécharger</a>
                                        </div>
                                    </div>
                                </div>
                            `;

                            row.append(html);
                        });

                        $('#contractsList').append(row);

            
                       
                    },
                    error: function () {
                        $('#contractsList').html('<p>Error loading contracts.</p>');
                    }
                });  
            });

            /* $(selector + ' tbody').on('click', '.trash', function(e)
            {
                e.preventDefault();
                var IdCharge  = $(this).attr('value');
                swal({
                    title: "es-tu sûr de supprimer cette charge",
                    text: "Une fois supprimée, vous ne pourrez plus récupérer cette charge !",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete)
                    {
                        var data =
                        {
                            'id'         : IdCharge,
                            '_token'     : csrf_token,
                        };
                        $.ajax({
                            type: "post",
                            url: TrashCharge,
                            data: data,

                            dataType: "json",
                            success: function (response)
                            {
                                if(response.status == 200)
                                {
                                    swal("Votre charge a été supprimée !", {
                                        icon: "success",
                                    });
                                    $('.TableCharge').DataTable().ajax.reload();
                                }
                                else if(response.status ==400)
                                {
                                    swal("Oops !", response.message, "error");
                                }
                                else if(response.status ==404)
                                {
                                    swal("Oops !", response.message, "error");
                                }
                            }
                        });

                    }
                    else
                    {
                        swal("Votre charge est sécurisée !");
                    }
                    });
            });

            $(selector + ' tbody').on('click', '.ChangeDate', function(e)
            {
                e.preventDefault();
                var idcharge = $(this).attr('value');
                $('#idCharge').val(idcharge);
                $('#ModelChargeEditDate').modal('show');
            }); */


           
        }
    });


    $('#EditClient').on('click',function(e)
    {
        e.preventDefault();
        let firstname = $('#NomClientEdit').val();
        let lastname  = $('#PrenomClientEdit').val();
        let cin       = $('#CinClientEdit').val();
        let address   = $('#AddressClientEdit').val();
        let phone     = $('#PhoneClientEdit').val();

        $.ajax({
            type: "post",
            url: UpdateClient,
            data: 
            {
                'firstname' : firstname,
                'lastname'  : lastname,
                'cin'       : cin,
                'address'   : address,
                'phone'     : phone,
                '_token'    : csrf_token,
                'id'        : $(this).attr('data-value'),
            },
            dataType: "json",
            success: function(response) 
            {
                if(response.status == 200) 
                {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditClient').modal('hide');
                    $('.Table_Client').DataTable().ajax.reload();
                    
                } 
                else if(response.status == 400) 
                {
                    $('.ValidationEditClient').html("");
                    $('.ValidationEditClient').addClass('alert alert-danger');

                    $.each(response.errors, function(key, list_err) {
                        $('.ValidationEditClient').append('<li>' + list_err + '</li>');
                    });
    
                    setTimeout(() => {
                        $('.ValidationEditClient').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } 
                else if(response.status == 500)
                {
                    new AWN().alert(response.message, {durations: {alert: 5000}}) ;  
                }   
            }
        }); 

    });
    

    $('#printImage').on('click', function() {
        var content = $('.ContentForPictureCinCustomer').html(); // Get the content inside the div

    // Open a new print window
    var printWindow = window.open('', '', 'height=600,width=800');

    // Write the content into the new window
    printWindow.document.write('<html><head><title>Impression</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }</style>'); // Optional CSS for styling
    printWindow.document.write('</head><body>');
    printWindow.document.write(content); // Insert the content inside the body
    printWindow.document.write('</body></html>');

    // Wait for the document to fully load and then print it
    printWindow.document.close(); // Close the document
    printWindow.print(); // Trigger the print functionality
    });
});