document.querySelectorAll('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!window.confirm(form.dataset.confirm))event.preventDefault()}));
document.querySelectorAll('input[type="file"]').forEach(input=>input.addEventListener('change',()=>{if(input.files[0]&&input.files[0].size>2*1024*1024){alert('Please choose an image under 2 MB.');input.value='';}}));
