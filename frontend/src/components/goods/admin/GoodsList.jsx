import React from "react";
import GoodsItem from "./GoodsItem";

const GoodsList = ({goods}) => {
    return <>
        <div>
            {goods && goods.map((item, key) => (
                <div key={key}>
                    <GoodsItem good={item}/>
                </div>
            ))}
        </div>
    </>
}
export default GoodsList;