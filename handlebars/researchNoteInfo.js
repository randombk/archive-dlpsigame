(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['researchNoteInfo.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\r\n	<span class=\"textLink\" data-hover=\"Buildings required to create this research note\">\r\n		Building Requirements:\r\n	</span>\r\n	<div style=\"margin-left: 10px;\">\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.researchNoteBuildingReq, options) : helperMissing.call(depth0, "key_value", depth0.researchNoteBuildingReq, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</div>\r\n	<br>\r\n";
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<span class=\"buildingLink\" data-buildID=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-buildLevel=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " -- ";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\r\n		";
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\r\n	<span class=\"textLink\" data-hover=\"Initial cost of creating this research note. This will not be refunded if the research is cancelled.\">\r\n		Research Start Cost:\r\n	</span>\r\n	<div style=\"margin-left: 10px;\">\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data};
  stack2 = ((stack1 = helpers.key_value_object || depth0.key_value_object),stack1 ? stack1.call(depth0, depth0.researchNoteCost, options) : helperMissing.call(depth0, "key_value_object", depth0.researchNoteCost, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</div>\r\n<br>\r\n";
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<span class=\"itemLink\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-parameters=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " -- ";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\r\n		";
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\r\n	<span class=\"textLink\" data-hover=\"Hourly resource consumption for creating this research note. Research will be cancelled if resources are missing.\">\r\n		Research Hourly Cost:\r\n	</span>\r\n	<div style=\"margin-left: 10px;\">\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data};
  stack2 = ((stack1 = helpers.key_value_object || depth0.key_value_object),stack1 ? stack1.call(depth0, depth0.researchNoteConsumption, options) : helperMissing.call(depth0, "key_value_object", depth0.researchNoteConsumption, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</div>\r\n	<br>\r\n";
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\r\n	<span class=\"textLink\" data-hover=\"Passive modifiers that will be active while the research is progressing.\">\r\n		Research Passive:\r\n	</span>\r\n	<div style=\"margin-left: 10px;\">\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(10, program10, data),data:data};
  stack2 = ((stack1 = helpers.key_value_object || depth0.key_value_object),stack1 ? stack1.call(depth0, depth0.researchNotePassive, options) : helperMissing.call(depth0, "key_value_object", depth0.researchNotePassive, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</div>\r\n";
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<span class='modLink' data-modID='";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "' data-amount='";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "'>";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " -- ";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\r\n		";
  return buffer;
  }

  options = {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.researchNoteBuildingReq, options) : helperMissing.call(depth0, "ifdef", depth0.researchNoteBuildingReq, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n\r\n";
  options = {hash:{},inverse:self.noop,fn:self.program(4, program4, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.researchNoteCost, options) : helperMissing.call(depth0, "ifdef", depth0.researchNoteCost, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n\r\n";
  options = {hash:{},inverse:self.noop,fn:self.program(7, program7, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.researchNoteConsumption, options) : helperMissing.call(depth0, "ifdef", depth0.researchNoteConsumption, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n\r\n";
  options = {hash:{},inverse:self.noop,fn:self.program(9, program9, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.researchNotePassive, options) : helperMissing.call(depth0, "ifdef", depth0.researchNotePassive, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n";
  return buffer;
  });
})();