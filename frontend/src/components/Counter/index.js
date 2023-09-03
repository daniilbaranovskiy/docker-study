import {useState} from "react";
import styled from "styled-components";

const StyledCounter = styled.div`
  padding: ${(props) => props.padding}px;
  background-color: ${(props) =>
          props.currentValue < 0
                  ? "red"
                  : props.currentValue > 30
                          ? "green"
                          : "white"};
`;

function Counter({value = 0, padding = 20}) {
    const [currentValue, setValue] = useState(value);

    return (
        <StyledCounter
            padding={padding}
            currentValue={currentValue}
        >
            <div>Value: {currentValue}</div>
            <div>
                <button
                    onClick={() => {
                        setValue(currentValue + 1);
                    }}
                >
                    +
                </button>
                <button
                    onClick={() => {
                        setValue(currentValue - 1);
                    }}
                >
                    -
                </button>
            </div>
        </StyledCounter>
    );
}
export default Counter;
