document.addEventListener('DOMContentLoaded', function () {
    const cardCU = document.getElementById('card-cu');
    const cardGK = document.getElementById('card-gk');
    const cardBI = document.getElementById('card-bi');

    const collapseOneEl = document.getElementById('collapseOne');
    const collapseTwoEl = document.getElementById('collapseTwo');
    const collapseThreeEl = document.getElementById('collapseThree');

    if (cardCU && cardGK && cardBI && collapseOneEl && collapseTwoEl && collapseThreeEl) {
        const collapseCU = new bootstrap.Collapse(collapseOneEl, { toggle: false });
        const collapseGK = new bootstrap.Collapse(collapseTwoEl, { toggle: false });
        const collapseBI = new bootstrap.Collapse(collapseThreeEl, { toggle: false });

        let isCUOpen = false;
        let isGKOpen = false;
        let isBIOpen = false;

        cardCU.addEventListener('click', function () {
            if (isCUOpen) {
                collapseCU.hide();
            } else {
                collapseCU.show();
                collapseGK.hide();
                collapseBI.hide();
                isGKOpen = false;
                isBIOpen = false;
                document.getElementById('headingOne').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isCUOpen = !isCUOpen;
        });

        cardGK.addEventListener('click', function () {
            if (isGKOpen) {
                collapseGK.hide();
            } else {
                collapseGK.show();
                collapseCU.hide();
                collapseBI.hide();
                isCUOpen = false;
                isBIOpen = false;
                document.getElementById('headingTwo').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isGKOpen = !isGKOpen;
        });

        cardBI.addEventListener('click', function () {
            if (isBIOpen) {
                collapseBI.hide();
            } else {
                collapseBI.show();
                collapseCU.hide();
                collapseGK.hide();
                isCUOpen = false;
                isGKOpen = false;
                document.getElementById('headingThree').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            isBIOpen = !isBIOpen;
        });
    }
});
