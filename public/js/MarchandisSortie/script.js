$(document).ready(function () {

    if (window.Laravel && window.Laravel.ErrorsInfos) {
       
        new AWN().alert(window.Laravel.ErrorsInfos, { durations: { alert: 5000 } });
    }
    if (window.Laravel && window.Laravel.ErrorsNumberStartBon) {
       
        new AWN().alert(window.Laravel.ErrorsNumberStartBon, { durations: { alert: 5000 } });
    }

    function initializeDataTableMArchandiseSortie(selector, url)
    {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().clear().destroy();
    }
    var tableLiveur = $(selector).DataTable({
        dom: 'Bfrtip', // لإظهار الأزرار
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export excel',
                className: 'btn btn-success',
                filename: 'table marchandise sorite'
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
            {data: 'clients'    , name: 'Client'     },
            {data: 'number_box' , name: 'Nombre'     },
            {data: 'cumul'      , name: 'Cumul'      },
            {data: 'name'       , name: 'Livreur'    },
            {data: 'matricule'  , name: 'Matricule'  },
            {
                data: 'type',
                name: 'type',
                render: function(data, type, row) {
                    return `<span class="badge bg-primary text-uppercase" style="font-size:15px">${data.charAt(0)}</span>`;
                }
            },
            {data: 'created'    , name: 'Créer par'  },
            {data: 'created_at' , name: 'Créer le'   },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        columnDefs: [
            {
                targets: '_all',
                createdCell: function(td) {
                    $(td).css('white-space', 'nowrap');
                }
            }
        ],
        language: {
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
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
    $(selector + ' tbody').on('click', '.DeleteMarchandiseSortie', function(e) 
    {
        e.preventDefault();
        let id = $(this).attr('data-id'); // جلب ID من الزر

        new AWN().confirm(
            "Êtes-vous sûr de vouloir supprimer ?", 
            function() {
                $.ajax({
                    type: "POST",
                    url: DeleteMarchandiseSortie, // الرابط
                    data: {
                        _token: csrf_token,
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            new AWN().success("Suppression effectuée avec succès !", { durations: { success: 5000 } });
                            $('.Table_Marchandis_Sortie').DataTable().ajax.reload(); // إعادة تحميل الجدول
                        } else {
                            new AWN().alert("Erreur : " + response.message, { durations: { alert: 5000 } });
                        }
                    },
                    error: function() {
                        new AWN().alert("Une erreur s'est produite, veuillez réessayer.", { durations: { alert: 5000 } });
                    }
                });
            }, 
            function() {
                new AWN().info("Suppression annulée");
            },
            {
                labels: {
                    confirm: "Oui, Supprimer !",
                    cancel: "Annuler"
                }
            }
        );
    });
    
}

$(function () {
    initializeDataTableMArchandiseSortie('.Table_Marchandis_Sortie', MarchandisSortie);
});
    // Variable for storing the DataTable instance
    let tableInstance;

    // Function to initialize the DataTable
    function initializeDataTable(selector, url, idclient) {
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().clear().destroy();
        }

        // Initialize a new DataTable instance
        tableInstance = $(selector).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                data: function (d) {
                    d.idclient = idclient;
                },
                dataSrc: function (json) {
                    // Hide the pagination when no data is available
                    if (json.data.length === 0) {
                        $('.paging_full_numbers').hide();
                    }
                    return json.data;
                }
            },
            columns: [
                {
                    data: 'cin', 
                    name: 'cin', 
                    className: 'text-nowrap'
                },
                {
                    data: 'matricule', 
                    name: 'matricule', 
                    className: 'text-nowrap'
                },
                {
                    data: 'livreur', 
                    name: 'livreur', 
                    className: 'text-nowrap'
                },
                {
                    data: 'name', 
                    name: 'name', 
                    className: 'text-nowrap'
                },
                {
                    data: 'name_client', 
                    name: 'name_client', 
                    className: 'text-nowrap'
                },
                {
                    data: 'total_quantity', 
                    name: 'total_quantity', 
                    className: 'text-nowrap text-end'
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-nowrap'
                }
                
            ],
            drawCallback: function(settings) {
                var api = this.api();
                var total = api.column(5, { page: 'current' }).data().reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                $('#total_quantity_footer').html(total);
            },
            language: {
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sLoadingRecords": "Chargement...",
                "sProcessing": "Traitement...",
                "sSearch": "Rechercher :",
                "sZeroRecords": "Aucun élément correspondant trouvé",
                "oPaginate": { "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent" },
                "select": { "rows": { "_": "%d lignes sélectionnées", "0": "Aucune ligne sélectionnée", "1": "1 ligne sélectionnée" } }
            }
        });
        $(selector + ' tbody').off('click', '.delete_tmp_machandise_sortie').on('click', '.delete_tmp_machandise_sortie', function(e) 
        {
            e.preventDefault();
            let idTmpMarchandiseSortie = $(this).attr('data-id');
            
            $.ajax({
                type    : "post",
                url     : TrashTmpMarchandiseSortieByProduct,
                data: {
                    id : idTmpMarchandiseSortie,
                    _token: csrf_token,
                },
                dataType: "json",
                success: function (response) 
                {
                    if(response.status == 200){
                        new AWN().success(response.message, { durations: { success: 5000 } });

                        // Clear the table and reinitialize it with updated data
                        $('#Table_Tmp_Marchandis_Sortie').DataTable().clear().destroy();  // Destroy the old DataTable instance
                        initializeDataTable('.Table_Tmp_Marchandis_Sortie', GetDataTmpMarchandiseSortie, response.idclient); // Reinitialize with new data
                    }    
                }
            });
        });
        $(selector + ' tbody').off('click', '.edit_tmp_machandise_sortie').on('click', '.edit_tmp_machandise_sortie', function(e) {
            e.preventDefault();
        
            let $btn = $(this);
            let $icon = $btn.find('i'); // <i class="mdi mdi-pencil-outline"></i>
            let isEditing = $icon.hasClass('mdi-pencil-outline');
            let $row = $btn.closest('tr');
            let table = $(selector).DataTable();
            let rowData = table.row($row).data();
            let $cell = $row.find('td').eq(5); // Column index for total_quantity
        
            if (isEditing) {
                // Switch to input mode
                let currentValue = rowData.total_quantity;
                $cell.html(`<input type="number" step="0.01" class="form-control form-control-sm edit-total-quantity" value="${currentValue}">`);
                $icon.removeClass('mdi-pencil-outline text-primary').addClass('mdi-check-circle-outline text-success');
            } else {
                // Switch back and update value
                let newQuantity = parseFloat($cell.find('input').val());
                let idTmpMarchandiseSortie = $btn.attr('data-id');

                // Validation
                if (isNaN(newQuantity) || newQuantity <= 0) {
                    new AWN().warning('Veuillez entrer une quantité valide (> 0)');
                    return;
                }
        
                $.ajax({
                    type: 'POST',
                    url: UpdateTmpMarchandiseQuantityURL, // Replace with your route
                    data: {
                        id: idTmpMarchandiseSortie,
                        total_quantity: newQuantity,
                        _token: csrf_token
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            $cell.html(newQuantity);
                            $icon.removeClass('mdi-check-circle-outline text-success').addClass('mdi-pencil-outline text-primary');
                            new AWN().success('Quantité mise à jour avec succès', { durations: { success: 3000 } });
                             // Clear the table and reinitialize it with updated data
                            $('#Table_Tmp_Marchandis_Sortie').DataTable().clear().destroy();  // Destroy the old DataTable instance
                            initializeDataTable('.Table_Tmp_Marchandis_Sortie', GetDataTmpMarchandiseSortie, response.idclient); // Reinitialize with new data
                        } else {
                            new AWN().warning('Erreur lors de la mise à jour');
                        }
                    }
                });
            }
        });
        
        

    }

    // Initial load of DataTable for the first time with idclient value
    let idclient = $('#idclient').val();
    initializeDataTable('.Table_Tmp_Marchandis_Sortie', GetDataTmpMarchandiseSortie, idclient);

    // Reload DataTable when idclient changes
    $('#idclient').on('change', function () {
        idclient = $(this).val();
        initializeDataTable('.Table_Tmp_Marchandis_Sortie', GetDataTmpMarchandiseSortie, idclient);
        
        
        $.ajax({
            type: "get",
            url: GetNomberCaisseByClient,
            data: 
            {
                id : $(this).val(),
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 200)
                {
                    $('#NombreBoxCaisseVide').text(response.Nombre_Caisse_Vide);
                    $('#NombreBoxMarchandisEntree').text(response.Nombre_Marchandise_Entree);
                    $('#NombreBoxMarchandisSortie').text(response.Nombre_Marchandise_Sortie);
                    $('#NombreBoxCaisseRetour').text(response.Nombre_Caisse_Retour);
                }
            }
        });
    
    });

    // Handling the 'AddInTmpMarchandiseSortie' click event
    $('#AddInTmpMarchandiseSortie').on('click', function (e) {
        e.preventDefault();
        
        // Collect the form data
        let formData = new FormData($('#FormAddMarchandiseSortie')[0]);
        formData.append('_token', csrf_token);

        // Disable the button and change its text while processing
        $('#AddInTmpMarchandiseSortie').prop('disabled', true).text('Enregistrement...');

        // Make the AJAX request
        $.ajax({
            type: "POST",
            url: AddProduitInTmpMarchandiseSortie,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                // Re-enable the button after processing
                $('#AddInTmpMarchandiseSortie').prop('disabled', false).text('Ajouter le produit');
                
                if (response.status == 200) {
                    // Show success notification
                    new AWN().success(response.message, { durations: { success: 5000 } });

                    // Clear the table and reinitialize it with updated data
                    $('.Table_Tmp_Marchandis_Sortie').DataTable().clear().destroy();  // Destroy the old DataTable instance
                    initializeDataTable('.Table_Tmp_Marchandis_Sortie', GetDataTmpMarchandiseSortie, idclient); // Reinitialize with new data

                    // Reset the form
                    //$('#FormAddMarchandiseSortie')[0].reset();
                } else if (response.status == 400) {
                    // Display validation errors if any
                    $('.ValidationMarchandiseSortie').html("").addClass('alert alert-danger');
                    $.each(response.errors, function (key, error) {
                        $('.ValidationMarchandiseSortie').append('<li>' + error + '</li>');
                    }); 

                    // Hide the validation message after 5 seconds
                    setTimeout(() => {
                        $('.ValidationMarchandiseSortie').fadeOut('slow', function () {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                } else if (response.status == 404 || response.status == 500) {
                    // Show alert if status is 404 or 500
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function () {
                // Handle any AJAX errors
                $('#AddInTmpMarchandiseSortie').prop('disabled', false).text('Ajouter le produit');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    $('#AddMarchandisSortie').on('click',function(e)
    {
        e.preventDefault();
        let idclient = $('#idclient').val();
        let etranger = $('#etranger').val();
        if(idclient == 0)
        {
            new AWN().alert("Veuillez sélectionner le client.", { durations: { alert: 5000 } });
            return false;
        }
        $('#AddMarchandisSortie').prop('disabled', true).text('Enregistrement...');
        $.ajax({
            type: "POST",
            url: StoreMarchandiseSortie,
            data: 
            {
                idclient : idclient,
                etranger : etranger,
                _token: csrf_token
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 200){
                    $('#ModalAddMarchandiseSortie').modal("hide");
                    $('.Table_Tmp_Marchandis_Sortie').DataTable().clear().destroy();

                    $('.Table_Marchandis_Sortie').DataTable().clear().destroy();
                    initializeDataTableMArchandiseSortie('.Table_Marchandis_Sortie', MarchandisSortie);

                    $('#AddMarchandisSortie').prop('disabled', false).text('Sauvegarder');
                }
            },
            error: function () {
                
                $('#AddMarchandisSortie').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        }); 
    });

    $('#achteur').on('change',function(e)
    {
        e.preventDefault();
        var idachteur = $(this).val();
        var idvendeur = $('#idvendeur').val();
        if(idvendeur != 0 && idachteur !=0)
        {
            if(idvendeur == idachteur)
            {
                new AWN().alert("Erreur : Vous ne pouvez pas sélectionner  pour le même vendeur.", { durations: { alert: 5000 } });
                $(this).val(0).change();
                return false;
            }
        }
        if(idvendeur == 0 && idachteur !=0)
        {
            new AWN().alert("Erreur : Veuillez sélectionner vendeur.", { durations: { alert: 5000 } });
            $(this).val(0).change();
            return false;
        }
    });
});
