document.addEventListener('DOMContentLoaded', function(){

	let chatbox = u('#chatbox');
	let form = u('form').attr({name: "message"}).first();
	let userMsg = u('#usermsg').first();
	userMsg.value = "";

	let loadInterval = setInterval(getLog, 1000);

	u(form).on('submit', (e) => {
		e.preventDefault();

		fetch(`/room/${ROOM_ID}`, {
			method: 'POST',
			headers: {
			  'Accept': 'application/json',
			  'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				username: USERNAME,
				message: userMsg.value
			})
		})
		.then((r) => {
			if(!r.ok){
				throw new Error('');
			}

			return r.json();
		})
		.then((data) => {

			userMsg.value = "";
		})
		.catch((e) => {
			onError('Failed to send message.');
		});
	});

	function getLog(){

		fetch(`/room/${ROOM_ID}/log`)
		.then((r) => {
			if(!r.ok){
				throw new Error('');
			}

			return r.text();
		})
		.then((data) => {

			chatbox.html(data);

			if(chatbox.children().last()){
				u(chatbox.children().last()).scroll();
			}	
		})
		.catch((e) => {
			onError('Failed to load chat data.');
		});
	}

	function onError(message){

		alert(message);
		clearInterval(loadInterval);
	}
});