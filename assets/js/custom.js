const selector = document.getElementById('product-category')
selector.addEventListener('change', query)

async function query(){
  const config ={
    method: 'POST',
    headers: {
      'Content-type': 'multipart/form-data; application/json'
    },
    credentials: 'same-origin',
    body:{
      action: "gpFilterProducts",
      category: selector.selectedOptions[0].value
    }
  }
  await fetch(gp.ajaxurl, config)
	.then(response=>response.json())
	.then(response=>console.log(response))

}

