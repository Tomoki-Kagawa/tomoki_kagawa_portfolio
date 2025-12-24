cmake_minimum_required(VERSION 3.10.2)

set(PROJECT BreakingBlocks)
set(CMAKE_AUTOMOC ON)
set(CMAKE_INCLUDE_CURRENT_DIR ON)
set(CMAKE_CXX_STANDARD 11)

project(${PROJECT})
find_package(Qt5Widgets REQUIRED)
find_package(YARP REQUIRED)
find_package(OpenCV REQUIRED)

include_directories(
    ${OpenCV_INCLUDE_DIRS}
)

add_executable(${PROJECT} 
    main.cpp
    BreakingBlock.cpp
    BreakingBlock.h
    Gameover.cpp
    Gameover.h
    Gameclear.cpp
    Gameclear.h
)
qt5_use_modules(${PROJECT} Widgets)

target_link_libraries(${PROJECT}
    ${YARP_LIBRARIES}
    ${OpenCV_LIBS}
)

install(TARGETS ${PROJECT}
    DESTINATION bin
)

