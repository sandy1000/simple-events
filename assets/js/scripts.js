let _ptcheckbox = document.getElementById('pt_expiry_check');
let _expirybox = document.getElementById('pt_expiry_date');
let _expirydate = document.getElementById('pt_br_news_expiry_date');
_ptcheckbox.addEventListener("change", expiryhideshow, false);

function expiryhideshow(){
  var isChecked = _ptcheckbox.checked;
  if(isChecked){ //checked
    _expirybox.style.opacity = '1';
    _expirybox.style.height = 'auto';
  }else{ //unchecked
    _expirybox.style.opacity = '0';
    _expirybox.style.height = '0';
    _expirydate.value = '';
  }
}