function confirmChange(plan) {
    var messageBox = document.getElementById('messageBox');
    messageBox.innerHTML = "Do you want to change your subscription to " + plan + "?";

    var yesBtn = document.createElement('button');
    yesBtn.textContent = 'Yes';
    yesBtn.className = 'yesBtn';

    var noBtn = document.createElement('button');
    noBtn.textContent = 'No';
    noBtn.className = 'noBtn';

    while (messageBox.firstChild) {
        messageBox.removeChild(messageBox.firstChild);
    }
    var textNode = document.createTextNode("Do you want to change your subscription to " + plan + "?");
    messageBox.appendChild(textNode);
    messageBox.appendChild(document.createElement('br'));
    messageBox.appendChild(yesBtn);
    messageBox.appendChild(noBtn);

    yesBtn.onclick = function () {
        document.getElementById('planInput').value = plan;
        document.getElementById('subscriptionForm').submit();
    };
    noBtn.onclick = function () {
        messageBox.style.display = 'none';
    };

    messageBox.style.display = 'block';
}

function toggleAutoRenew(checkbox) {
    var form = document.getElementById('autoRenewForm');
    var input = document.getElementById('autoRenewInput');
    input.value = checkbox.checked ? '1' : '0';
    form.submit();
}

window.onload = function () {
    var messageBox = document.getElementById('messageBox');
    var message = messageBox.getAttribute('data-message');
    if (message) {
        messageBox.textContent = message;
        var closeBtn = document.createElement('button');
        closeBtn.textContent = 'Close';
        closeBtn.className = 'closeBtn';
        closeBtn.onclick = function () {
            messageBox.style.display = 'none';
        };
        messageBox.appendChild(document.createElement('br'));
        messageBox.appendChild(closeBtn);
        messageBox.style.display = 'block';
    }
};
