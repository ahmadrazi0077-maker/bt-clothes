fetch('/test-cart', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ variant_id: 'test', quantity: 1 })
})
.then(response => response.json())
.then(data => console.log('Test response:', data))
.catch(error => console.error('Test error:', error));

