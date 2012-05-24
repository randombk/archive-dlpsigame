/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/*
 * Cross-Window communication with localStorage using the jStorage library
 **/
"use strict";
function Message(msgType, msgData, msgTarget, msgSender) {
	this.objectType = "windowMessage";

	this.msgType	= msgType;
	this.msgData	= msgData;
	this.msgTarget	= msgTarget; //Specify target for message
	this.msgSender	= msgSender; //Specify message sender
}
