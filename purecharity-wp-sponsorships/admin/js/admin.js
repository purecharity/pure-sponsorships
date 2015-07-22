$(document).ready(function(){

  /* Custom Fields Sortable Config */
  $( ".sortable" ).sortable({
    stop: function(){
      save_custom_fields();
    },
    axis: 'Y'
  });
  $( ".sortable" ).disableSelection();

  // For editing of each field
  $(document).on('click', '.custom_field a.edit', function(){
    $(this).hide();
    $(this).parent().find('b').hide();
    $(this).parent().find('input').show();
    $(this).parent().find('.save').show();
    return false;
  })
  $(document).on('click', '.custom_field a.save', function(){
    var text = $(this).parent().find('input').val();
    if(text == ''){ alert("Value can't be empty."); return false; }
    $(this).hide();
    $(this).parent().find('b').show().text(text);
    $(this).parent().find('input').hide();
    $(this).parent().find('.edit').show();
    save_custom_fields();
    return false;
  })

  // Prevents enter tu submit the form
  $(document).on('keydown', '.custom_field input', function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      $(this).parent().find('.save').click();
      return false;
    }
  })
  $(document).on('keydown', '#example-program-slug', function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      $(this).next().click();
      return false;
    }
  })

  // Remove an item
  $(document).on('click', '.custom_field .remove', function(event){
    if(confirm("Are you sure you want to remove this field?")){
      $(this).parents('li').remove();
      save_custom_fields();
    }
    return false;
  })
  /* Custom Fields Sortable Config */

  $('#custom-fields-example').on('click', function(){
    $(this).hide()
    $('#custom-fields-example-cancel').show()
    $('#custom-fields-loader').show()
    return false;
  })
  $('#custom-fields-example-cancel').on('click', function(){
    $(this).hide()
    $('#custom-fields-example').show()
    $('#custom-fields-loader').hide()
    return false;
  })

  $('#generate-example').on('click', function(){
    $('#generate-example').text('Loading...');
    var slug = $('#example-program-slug').val()

    $.ajax({
      type: 'GET',
      // dataType: 'jsonp',
      xhrFields: { withCredentials: true },
      url: $(this).parent().attr('data-api-url')+slug,
      success: function(data){
	      parsed_data = JSON.parse(data.custom_fields);

				var existing_fields = Array();
				Object.keys(parsed_data).forEach(function (key) {
					if(field_exists(key)){
						existing_fields.push(key);
					}else{
						// console.log(key)
						// console.log(parsed_data[key])
						new_example_custom_field(key, parsed_data[key]);
					}
				});

				if(existing_fields.length > 0){
					alert("The following fields already exist and were not imported:\n"+existing_fields.join('\n'))
				}
			},
      error: function(e){ console.log(e) },
			complete: function(data){
				$('#generate-example').text('Load Example');
			}
    });
  })
})

// Custom Fields manager
function save_custom_fields(){
  var custom_fields = Array();
  $('li.custom_field').each(function(){
    custom_fields.push($(this).find('.right b:first').text()+'|'+$(this).find('.left b:first').text())
  })
  $('#custom_fields_value').val(custom_fields.join(';'))
  return false;
}

// Check if a field already exists
function field_exists(key){
	var exists = false;
	$('.custom_field').each(function(){
		if($(this).find('.right b:first').text() == key){
			exists = true;
			return exists;
		}
	})
	return exists;
}

// Add a new example custom field
function new_example_custom_field(original, display){
  var html = '<li class="custom_field">'
      + '<div class="left">'
      + '<b>'+display+'</b>'
      + '<input type="text" value="'+display+'">'
      + ' <a href="#" class="edit">edit</a>'
      + ' <a href="#" class="save">save</a>'
      + '</div>'
      + '<div class="right">'
      + '<b>'+original+'</b>'
      + '<input type="text" value="'+original+'">'
      + ' <a href="#" class="edit">edit</a>'
      + ' <a href="#" class="save">save</a>'
      + '</div>'
      + '<div class="options">'
      + '<a href="#" class="remove">remove</a>'
      + '</div>'
      + '<br style="clear:both" />'
      + '</li>';
  $('ul.pcs_custom_field:last').append(html);
}

// Add a new custom field
function new_custom_field(){
  var html = '<li class="custom_field">'
      + '<div class="left">'
      + '<b>Display Value</b>'
      + '<input type="text" value="Display Value">'
      + ' <a href="#" class="edit">edit</a>'
      + ' <a href="#" class="save">save</a>'
      + '</div>'
      + '<div class="right">'
      + '<b>CustomFieldIdentifier</b>'
      + '<input type="text" value="CustomFieldIdentifier">'
      + ' <a href="#" class="edit">edit</a>'
      + ' <a href="#" class="save">save</a>'
      + '</div>'
      + '<div class="options">'
      + '<a href="#" class="remove">remove</a>'
      + '</div>'
      + '<br style="clear:both" />'
      + '</li>';
  $('ul.pcs_custom_field:last').append(html);
}
