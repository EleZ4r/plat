// Select DOM elements
const addMemberBtn = document.getElementById('addMemberBtn');
const nameInput = document.getElementById('nameInput');
const blockInput = document.getElementById('blockInput');
const membersList = document.getElementById('membersList');

// Function to render the members list from PHP (AJAX)
function renderMembers() {
    fetch('load_members.php')
        .then(response => response.json())
        .then(members => {
            membersList.innerHTML = '';
            members.forEach((member, index) => {
                const memberDiv = document.createElement('div');
                memberDiv.classList.add('member');
                memberDiv.dataset.index = index;

                const nameInputElement = document.createElement('input');
                nameInputElement.value = member.name;
                nameInputElement.setAttribute('readonly', true);

                const blockInputElement = document.createElement('input');
                blockInputElement.value = member.block;
                blockInputElement.setAttribute('readonly', true);

                const editBtn = document.createElement('button');
                editBtn.textContent = 'Edit';
                editBtn.onclick = () => editMember(index);

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Delete';
                deleteBtn.onclick = () => deleteMember(index);

                memberDiv.appendChild(nameInputElement);
                memberDiv.appendChild(blockInputElement);
                memberDiv.appendChild(editBtn);
                memberDiv.appendChild(deleteBtn);

                membersList.appendChild(memberDiv);
            });
        });
}

// Add Member Function
addMemberBtn.onclick = () => {
    const name = nameInput.value.trim();
    const block = blockInput.value.trim();

    if (name && block) {
        fetch('save_member.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'add',
                name: name,
                block: block
            })
        })
        .then(() => {
            nameInput.value = '';
            blockInput.value = '';
            renderMembers();
        });
    } else {
        alert('Please enter both name and block');
    }
};

// Edit Member Function
function editMember(index) {
    const nameInputElement = membersList.querySelector(`[data-index="${index}"] input:nth-child(1)`);
    const blockInputElement = membersList.querySelector(`[data-index="${index}"] input:nth-child(2)`);

    if (nameInputElement.readOnly) {
        nameInputElement.removeAttribute('readonly');
        blockInputElement.removeAttribute('readonly');
        nameInputElement.focus();
    } else {
        const name = nameInputElement.value;
        const block = blockInputElement.value;

        fetch('save_member.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'edit',
                index: index,
                name: name,
                block: block
            })
        })
        .then(() => {
            nameInputElement.setAttribute('readonly', true);
            blockInputElement.setAttribute('readonly', true);
            renderMembers();
        });
    }
}

// Delete Member Function
function deleteMember(index) {
    fetch('save_member.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'delete',
            index: index
        })
    })
    .then(() => renderMembers());
}

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop(); // Get the current page file name
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');  // Add 'active' class to the current page link
        } else {
            link.classList.remove('active');  // Remove 'active' class from other links
        }
    });
});

  
