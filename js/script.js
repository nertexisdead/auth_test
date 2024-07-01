$(document).ready(function() {
    $('#editForm').submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'edit.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                    $('#userName').text(response.user.name);
                    $('.navbar-name').text(response.user.name);
                    $('#userEmail').text(response.user.email);
                    $('#userPhone').text(response.user.phone);

                    $('#successAlert').alert();
                    $('#alertContainer').show();

                    $('#editModal').modal('hide');

                    setTimeout(function() {
                        $('#alertContainer').hide();
                    }, 5000);
                } else {
                    alert('Ошибка при обновлении данных: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error: ' + status + ' - ' + error);
            }
        });
    });
});