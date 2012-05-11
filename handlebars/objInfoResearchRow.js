(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['objInfoResearchRow.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			"
    + escapeExpression(((stack1 = ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Weapons)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\r\n		";
  return buffer;
  }

function program3(depth0,data) {
  
  
  return "\r\n			0\n		";
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			"
    + escapeExpression(((stack1 = ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Defense)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\r\n		";
  return buffer;
  }

function program7(depth0,data) {
  
  
  return "\r\n			0\r\n		";
  }

function program9(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			"
    + escapeExpression(((stack1 = ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\r\n		";
  return buffer;
  }

function program11(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			"
    + escapeExpression(((stack1 = ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Economic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\r\n		";
  return buffer;
  }

function program13(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			"
    + escapeExpression(((stack1 = ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Fleet)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\r\n		";
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<input class=\"activity_";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " activityInput\" data-id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" type=\"number\" value=\"";
  if (stack1 = helpers.activity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.activity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" min=\"0\" max=\"100\">%\r\n		";
  return buffer;
  }

  buffer += "<tr class=\"gen\">\r\n	<td>";
  if (stack1 = helpers.itemName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n	<td style=\"text-align: center;\">\r\n		";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Weapons), {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td style=\"text-align: center;\">\r\n		";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Defense), {hash:{},inverse:self.program(7, program7, data),fn:self.program(5, program5, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td style=\"text-align: center;\">\r\n		";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic), {hash:{},inverse:self.program(7, program7, data),fn:self.program(9, program9, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td style=\"text-align: center;\">\r\n		";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Economic), {hash:{},inverse:self.program(7, program7, data),fn:self.program(11, program11, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td style=\"text-align: center;\">\r\n		";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.research),stack1 == null || stack1 === false ? stack1 : stack1.Fleet), {hash:{},inverse:self.program(7, program7, data),fn:self.program(13, program13, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td>\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(15, program15, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.activity, options) : helperMissing.call(depth0, "ifdef", depth0.activity, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n</tr>";
  return buffer;
  });
})();