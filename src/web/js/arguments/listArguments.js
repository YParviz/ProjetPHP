function vote(idArgument) {
    fetch( '/vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            "idArgument": idArgument
        })
    }).then(response => {
            if (response.ok){
                return response.text()
            } else {
                throw new Error("Un problème est survenu")
            }
        }
    ).then(data => {
        document.getElementById("numVoteArg" + idArgument).innerText = data + " votes"
    })
        .catch(error => {
            alert("Un problème est survenu")
        })
}

function unvote(idArgument) {
    fetch( '/unvote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            "idArgument": idArgument
        })
    }).then(response => {
            if (response.ok){
                return response.text()
            } else {
                throw new Error("Un problème est survenu")
            }
        }
    ).then(data => {
        document.getElementById("numVoteArg" + idArgument).innerText = data + " votes"
    })
        .catch(error => {
            alert("Vous avez déjà voté pour cet argument")
        })
}