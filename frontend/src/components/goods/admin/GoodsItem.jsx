import React from "react";

const GoodsItem = ({good}) => {
    return <>
        <div>
            <p>{good.name}</p>
        </div>
    </>
}
export default GoodsItem;