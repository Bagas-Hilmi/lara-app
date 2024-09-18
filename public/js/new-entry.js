document.getElementById('saveEntry').addEventListener('click', function() {
    const form = document.getElementById('entryForm');
    const formData = new FormData(form);

    // Pastikan `mode` sesuai
    formData.append('mode', 'ADD'); // Atur mode untuk ADD

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Entry saved successfully!');

            // Reload DataTable tanpa refresh halaman
            $('#cipCumBalTable').DataTable().ajax.reload(null, false);

            // Tutup modal setelah sukses
            $('#new-form').modal('hide');
        } else {
            alert(data.message || 'An error occurred while saving.');
        }
    })
    .catch(error => console.error('Error:', error));
});
