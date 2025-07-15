$(document).ready(function () {
    $('#btnValidate').on('click', function () {
        let dotation = $('#inputDotation').val();
        let charge_id = $('#inputCharge').val();
        let montant = $('#inputMontant').val();
       
        if (montant !== '' && (charge_id === '0' || charge_id === 0)) {
            
            new AWN().warning("veuillez sélectionner la charge", {durations: {success: 0}});
            $('#inputCharge').focus(); // optional: focus on inputCharge field
            return false;
        }

        if(dotation == '' && charge_id === '0' || charge_id === 0 && montant == 0)
        {
             new AWN().warning("veuillez sélectionner la charge ou entre dotatin or montant", {durations: {success: 0}});
            return false;
        }
        // Convert empty strings to null
        dotation = dotation ? parseFloat(dotation) : null;
        montant = montant ? parseFloat(montant) : null;

        // If charge is "veuillez sélectionner", treat it as null
        if (charge_id === "0") {
            charge_id = null;

            // Si montant est rempli mais pas de charge => montant = 0
            if (montant !== null) {
                montant = 0;
            }
        }
        $.ajax({
            url: store,
            method: 'POST',
            data: {
                _token: csrf_token,
                dotation: dotation,
                charge_id: charge_id,
                montant: montant
            },
            success: function (response) {
                if (response.success) {
                    new AWN().success(response.message, {durations: {success: 0}});
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert("Une erreur s'est produite lors de l'ajout.");
                }
            }/* ,
            error: function (xhr) {
                alert("Veuillez vérifier la validité des données.");
                console.error(xhr.responseText);
            } */
        });
    });

});