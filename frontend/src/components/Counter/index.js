import {useState} from "react";

function Counter({value = 0, padding = 20, color = "white"}) {
    const [currentValue, setValue] = useState(value)
    return <div style={{padding, backgroundColor: (currentValue < 0 ? color : "white")}}>
        <div>Value: {currentValue}</div>
        <div>
        <button onClick={() => {
        setValue(currentValue + 1)
    }}>+
    </button>
    <button onClick={() => {
        setValue(currentValue - 1)
    }}>-
    </button>
</div>
</div>
}

export default Counter;