document.addEventListener('DOMContentLoaded', function () {
    // Dark mode toggle
    var toggle = document.querySelectorAll('.dark-toggle');
    toggle.forEach(function(btn){
        btn.addEventListener('click', function(){
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('dark-mode', document.body.classList.contains('dark-mode'));
        });
    });

    if (localStorage.getItem('dark-mode') === 'true') {
        document.body.classList.add('dark-mode');
    }
});
