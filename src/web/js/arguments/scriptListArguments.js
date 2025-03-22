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
        console.log(response);
            if (response.ok){
                return response.text()
            } else {
                throw new Error("Un problème est survenu")
            }
        }
    ).then(data => {
        document.getElementById("imgVoteArg" + idArgument).setAttribute("src", "../../image/arguments/unvote.png");
        document.getElementById("imgVoteArg" + idArgument).setAttribute("onclick", "unvote(" + idArgument + ")");
        document.getElementById("imgVoteArg" + idArgument).setAttribute("id", "imgVoteArg" + idArgument);
        document.getElementById("numVoteArg" + idArgument).innerText = data + " votes";
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
        console.log(response);
            if (response.ok){
                return response.text()
            } else {
                throw new Error("Un problème est survenu")
            }
        }
    ).then(data => {
        document.getElementById("imgVoteArg" + idArgument).setAttribute("src", "../../image/arguments/vote.png");
        document.getElementById("imgVoteArg" + idArgument).setAttribute("onclick", "vote(" + idArgument + ")");
        document.getElementById("numVoteArg" + idArgument).innerText = data + " votes";

    })
}